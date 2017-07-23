<?php
/**
 * User: Administrator
 * Date: 2017/7/20
 * Time: 21:06
 */

namespace Joking\Kernel\Support;


use Joking\Kernel\Functions\Factory;

abstract class Singleton {

    protected static $instances = [];

    /**
     * @return $this
     */
    public static function current() {
        $className = get_called_class();
        if (array_key_exists($className, static::$instances)) {
            return static::$instances[$className];
        }

        static::$instances[$className] = Factory::instance($className);
        return static::$instances[$className];
    }
}