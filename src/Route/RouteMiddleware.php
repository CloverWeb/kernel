<?php
/**
 * User: Administrator
 * Date: 2017/7/24
 * Time: 20:30
 */

namespace Joking\Kernel\Route;


use Joking\Kernel\Functions\Factory;
use Joking\Kernel\Functions\Method;
use Joking\Kernel\Http\HttpRequest;
use Joking\Kernel\Support\Middleware;

class RouteMiddleware extends Middleware {

    /**
     * 获取分类
     * @return string
     */
    protected function getClassify() {
        return 'route';
    }

    public function execute($middleware, $params = []) {
        $middleware = class_exists($middleware) ? $middleware : $this->get($middleware);
        $middleware = Factory::instance($middleware);

        if ($middleware instanceof RouteMiddlewareInterface) {
            return $middleware->handle($params['routeResult']);
        }
    }
}