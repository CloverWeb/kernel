<?php
/**
 * User: Administrator
 * Date: 2017/7/24
 * Time: 13:01
 */

namespace Joking\Kernel\Functions;

use Joking\Kernel\Functions\Interfaces\ApplicationEvent;
use Joking\Kernel\Functions\Interfaces\ApplicationListen;
use Joking\Kernel\Support\Singleton;

class Event extends Singleton {

    protected $events = [];

    /**
     * 触发事件
     * @param ApplicationEvent $event 事件名称
     * @return void
     * @throws \Exception
     */
    public function touch(ApplicationEvent $event) {
        if ($event instanceof ApplicationEvent) {
            $eventClassName = get_class($event);
            if (isset($this->events[$eventClassName])) {
                foreach ($this->events[$eventClassName] as $listen) {
                    if ($this->callListener($listen, $event) === false) {
                        break;
                    }
                }
            }
        } else {
            throw new \Exception('事件不合法');
        }
    }

    /**
     * 注册事件
     * @param string $event 事件class的名称
     * @param string $listen 事件的监听者
     *
     */
    public function listen($event, $listen) {
        is_array($listen) || $listen = [$listen];
        if (isset($this->events[$event])) {
            $this->events[$event] = array_merge($this->events[$event], $listen);
        } else {
            $this->events[$event] = $listen;
        }
    }

    protected function callListener($listen, $event) {
        if (is_string($listen) && class_exists($listen)) {
            $listen = Factory::instance($listen);
        }

        if ($listen instanceof ApplicationListen) {
            return $listen->handle($event);
        } else {
            return Method::execute($listen, [$event]);
        }
    }
}