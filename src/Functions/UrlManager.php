<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 23:01
 */

namespace Joking\Kernel\Functions;


use Joking\Kernel\Route\FastRoute;
use Joking\Kernel\Support\Singleton;

class UrlManager extends Singleton {

    protected $fastRoute;

    public function __construct() {
        $this->fastRoute = new FastRoute();
    }

    public function to($path, $params = []) {
        $paramString = $this->formatParams($params);
        $paramString = empty($paramString) ? '' : '?' . $paramString;
        return '/' . trim($path, '&/?\'') . $paramString;
    }

    public function route($name, $params) {
        if ($routeEntity = $this->fastRoute->getRoute($name)) {
            $url = preg_replace_callback('/\{(.*?)(:.*?)?(\{[0-9,]+\})?\}/', function ($m) use (&$params) {
                if (isset($params[$m[1]])) {
                    $value = $params[$m[1]];
                    unset($params[$m[1]]);
                    return $value;
                }

                throw new \Exception('参数不存在：' . $m[1]);
            }, $routeEntity->url);

            return $this->to($url, $params);
        }

        throw new \Exception('找不到该路由 ： ' . $name);
    }

    protected function formatParams($params = []) {
        if (empty($params)) {
            return '';
        }
        return http_build_query($params);
    }
}