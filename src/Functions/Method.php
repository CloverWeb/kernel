<?php
/**
 * User: Administrator
 * Date: 2017/7/21
 * Time: 21:32
 */

namespace Joking\Kernel\Functions;

/**
 * 执行某一个类的方法，或者函数，或者匿名函数
 * Class Method
 * @package Joking\Kernel\Functions
 */
class Method {

    public static function execute($function, $params = []) {
        if (is_array($function) && count($function) == 2) {
            $object = array_shift($function);
            $method = array_shift($function);
            $object = is_object($object) ? $object : Factory::instance($object);

            $reflectionClass = new \ReflectionClass($object);
            if ($reflectionClass->hasMethod($method)) {
                $methodParams = FunctionParameters::get($reflectionClass->getMethod($method), $params);
                return call_user_func_array([$object, $method], $methodParams);
            }
        } else if ((is_string($function) && function_exists($function)) || $function instanceof \Closure) {

            $functionParams = FunctionParameters::get(new \ReflectionFunction($function), $params);
            return call_user_func_array($function, $functionParams);
        }

        throw new \Exception('不合法的参数 function');
    }
}