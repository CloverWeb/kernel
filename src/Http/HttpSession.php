<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 12:40
 */

namespace Joking\Kernel\Http;


use Joking\Kernel\Events\BeforeSessionOpenEvent;
use Joking\Kernel\Functions\Event;
use Joking\Kernel\Functions\Factory;
use Joking\Kernel\Http\Session\SessionHandler;
use Joking\Kernel\Http\Session\SessionInterceptor;
use Joking\Kernel\Http\Session\SessionInterceptorHandle;
use Joking\Kernel\Support\Singleton;

class HttpSession extends Singleton {

    protected $isOpen = false;

    protected $sessionHandler;

    public function __construct(SessionHandler $sessionHandler) {
        $this->sessionHandler = $sessionHandler;
    }

    public function open() {
        if ($this->isOpen == false) {
            Event::current()->touch(new BeforeSessionOpenEvent());
            session_start();
            $this->isOpen = true;
        }
    }

    public function get($name, $default = null) {
        $this->open();

        $stdClass = $this->intercept('get', $name, $default);
        if ($stdClass->isIntercept) {
            return $stdClass->value;
        }
        return $this->sessionHandler->get($stdClass->name, $default);
    }

    public function add($name, $value, $timeout = 0) {
        $this->open();

        $stdClass = $this->intercept('add', $name, $value, $timeout);
        if (!$stdClass->isIntercept) {
            $this->sessionHandler->add($stdClass->name, $stdClass->value, $stdClass->timeout);
        }
    }

    public function remove($name) {
        $this->open();

        $stdClass = $this->intercept('remove', $name);
        if (!$stdClass->isIntercept) {
            $this->sessionHandler->remove($stdClass->name);
        }
    }

    /**
     * 设置session 的名称
     * @param $name
     */
    public function setName($name) {
        session_name($name);
    }

    /**
     * 获取session的名称
     * @return string
     */
    public function getName() {
        $this->open();

        return session_name();
    }

    /**
     * 设置session id
     * @param null $id
     */
    public function setId($id = null) {
        session_id($id);
    }

    /**
     * 获取session id
     * @return string
     */
    public function getId() {
        $this->open();
        return session_id();
    }

    protected function intercept($handle, $name, $value = null, $timeout = 0) {
        $stdClass = new \stdClass();
        $stdClass->handle = $handle;
        $stdClass->name = $name;
        !is_null($value) && $stdClass->value = $value;
        $handle == 'add' && $stdClass->timeout = $timeout;


        $isIntercept = false;
        foreach (SessionInterceptor::current()->getIntercepts() as $intercept) {
            $classes = Factory::instance($intercept);
            if ($classes instanceof SessionInterceptorHandle) {
                $classes->isIntercept($stdClass) && $isIntercept = true;
                $classes->handle($stdClass);
            }
        }

        $stdClass->isIntercept = $isIntercept;
        return $stdClass;
    }
}