<?php
/**
 * User: Administrator
 * Date: 2017/7/21
 * Time: 21:25
 */

namespace Joking\Kernel\Support;

use Joking\Kernel\Functions\Factory;
use Joking\Kernel\Functions\Method;


/**
 * 借用 laravel 的 facade的方式
 * Class Facades
 * @package Joking\Kernel\Support
 */
abstract class Facades {

    protected static $facades = [];

    protected $methodMap = [];

    protected abstract function getFacadeAccessor(): Singleton;

    public static function __callStatic($method, $arguments) {
        $class = static::getFacadeAccessor();
        if ($realMethod = static::methodExists($class, $method)) {
            return Method::execute([$class, $realMethod], $arguments);
        }

        throw new \Exception('需要执行的方法不存在！');
    }

    /**
     * @param $class
     * @param $method
     * @return bool|string   返回真实执行的方法，或者 false
     */
    protected static function methodExists($class, $method) {
        $currentClass = static::getCurrentClass();
        $methodMap = $currentClass->getMethodMap();
        array_key_exists($method, $methodMap) && $method = $methodMap[$method];
        return method_exists($class, $method) ? $method : false;
    }

    /**
     * @return Facades
     */
    protected static function getCurrentClass() {
        $className = get_called_class();
        if (!isset(static::$facades[$className])) {
            static::$facades[$className] = Factory::instance($className);
        }
        return static::$facades[$className];
    }

    public function getMethodMap() {
        return $this->methodMap;
    }
}