<?php
/**
 * User: Administrator
 * Date: 2017/7/20
 * Time: 21:42
 */

namespace Joking\Kernel;

use Joking\Kernel\Events\BeforeRouteEvent;
use Joking\Kernel\Functions\Config;
use Joking\Kernel\Functions\Event;
use Joking\Kernel\Functions\Facades\Request;
use Joking\Kernel\Functions\Factory;
use Joking\Kernel\Functions\Method;
use Joking\Kernel\Functions\WhoopErrors;
use Joking\Kernel\Events\BeforeAppStartEvent;
use Joking\Kernel\Http\HttpRequest;
use Joking\Kernel\Http\HttpResponse;
use Joking\Kernel\Route\FastRoute;
use Joking\Kernel\Route\RouteEntity;
use Joking\Kernel\Route\RouteMiddleware;
use Joking\Kernel\Support\Singleton;

class Kernel extends Singleton {

    public function start($config) {

        //注册配置项
        Config::current()->register($config);

        //注册错误处理情况
        WhoopErrors::current()->register(Config::current()->get('debug'));

        //触发事件（程序开始之前的事件）
        Event::current()->touch(new BeforeAppStartEvent());

        $fastRoute = Factory::instance(FastRoute::class);
        $routeResult = $fastRoute->dispatch(Request::method(), Request::uri());

        //路由结果事件
        Event::current()->touch(new BeforeRouteEvent($routeResult));

        $handleResult = $this->handle($routeResult);

        return $this->response($handleResult);
    }

    public function handle(RouteEntity $routeResult) {

        //说明，中间件如果有返回值的话就直接输出了
        foreach ($routeResult->middleware as $middleware) {
            $middlewareResult = RouteMiddleware::current()->execute($middleware, ['routeResult' => $routeResult]);
            if ($middlewareResult !== null) {
                return $middlewareResult;
            }
        }

        if (is_string($routeResult->handle) && strpos($routeResult->handle, '@') !== false) {
            list($classes, $method) = explode('@', $routeResult->handle);
            return Method::execute([$classes, $method], $routeResult->params);
        }

        return Method::execute($routeResult->handle, $routeResult->params);
    }

    protected function response($handleResult) {
        if ($handleResult instanceof HttpResponse) {
            return $handleResult->send(HttpRequest::current());
        } else {
            HttpResponse::current()->setContent($handleResult);
            return HttpResponse::current()->send(
                HttpRequest::current()
            );
        }
    }
}