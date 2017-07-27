<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 20:41
 */

namespace Joking\Kernel\Route;


interface RouteMiddlewareInterface {

    public function handle(RouteEntity $routeResult);
}