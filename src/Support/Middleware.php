<?php
/**
 * User: Administrator
 * Date: 2017/7/24
 * Time: 21:02
 */

namespace Joking\Kernel\Support;


abstract class Middleware extends Singleton {

    protected static $middleware = [];

    /**
     * 获取分类
     * @return string
     */
    protected abstract function getClassify();

    public function register($name, $middleware) {
        $classify = $this->getClassify();
        if (!array_key_exists($classify, static::$middleware)) {
            static::$middleware[$classify] = [];
        }

        static::$middleware[$classify][$name] = $middleware;
    }

    public function get($name) {
        $classify = $this->getClassify();
        if (array_key_exists($classify, static::$middleware)) {
            if (array_key_exists($name, static::$middleware[$classify])) {
                return static::$middleware[$classify][$name];
            }
        }

        throw new \Exception($name . '：并没有被注册');
    }

    public function has($name) {
        $classify = $this->getClassify();
        if (array_key_exists($classify, static::$middleware)) {
            if (array_key_exists($name, static::$middleware[$classify])) {
                return true;
            }
        }

        return false;
    }

    public abstract function execute($middleware, $params = []);
}