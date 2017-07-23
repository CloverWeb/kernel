<?php

namespace Joking\Kernel\Functions;

class Factory {

    /**
     * 实例化某个类
     * @param string $abstract 需要实例化的类的名字
     * @param array $parameters 这个类的构造函数需要传入的参数
     * @param array $properties 需要注入的属性的值
     * @return object
     * @throws \Exception
     */
    public static function instance($abstract, $parameters = [], $properties = []) {
        if (!is_string($abstract) || !class_exists($abstract)) {
            throw new \Exception('找不到对象 ： ' . $abstract);
        }

        $reflectionClass = new \ReflectionClass($abstract);

        $methodParams = [];
        if ($reflectionClass->hasMethod('__construct')) {
            $methodParams = FunctionParameters::get($reflectionClass->getConstructor(), $parameters);
        }

        //实例化
        $instance = count($methodParams) > 0 ? $reflectionClass->newInstanceArgs($methodParams) : $reflectionClass->newInstance();

        //注入属性
        foreach ($properties as $name => $value) {
            if ($reflectionClass->hasProperty($name)) {
                $reflectionProperty = $reflectionClass->getProperty($name);
                $reflectionProperty->isPublic() || $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($instance, $value);
            }
        }

        return $instance;
    }
}