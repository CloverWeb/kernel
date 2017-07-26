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

    //按需加载
    private $parameters = [];

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

    public function __get($name) {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->parameters[$name] = call_user_func([$this, $method]);
            return $this->parameters[$name];
        }

        return null;
    }

    public function __isset($name) {
        return isset($this->parameters[$name]);
    }
}