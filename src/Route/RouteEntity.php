<?php
/**
 * User: Administrator
 * Date: 2017/7/23
 * Time: 14:36
 */

namespace Joking\Kernel\Route;


use Joking\Kernel\Support\Entity;

/**
 * Class RouteEntity
 * @property string[] middleware
 * @property array params
 * @property string name
 * @property string method
 * @property mixed handle
 * @package Joking\Kernel\Route
 */
class RouteEntity extends Entity {

    public function name($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $middleware 可以是className 也可以是别名
     * @return $this
     */
    public function middleware($middleware) {
        isset($this->middleware) || $this->middleware = [];
        is_string($middleware) && $middleware = [$middleware];
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }
}