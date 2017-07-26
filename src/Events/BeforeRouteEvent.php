<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 19:41
 */

namespace Joking\Kernel\Events;


use Joking\Kernel\Functions\Interfaces\ApplicationEvent;
use Joking\Kernel\Route\RouteEntity;

class BeforeRouteEvent implements ApplicationEvent {

    public $routeResult;

    public function __construct(RouteEntity $routeResult) {
        $this->routeResult = $routeResult;
    }
}