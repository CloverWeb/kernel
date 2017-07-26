<?php
/**
 * User: Administrator
 * Date: 2017/7/20
 * Time: 21:45
 */

namespace Joking\Kernel\Support;

class Entity implements \ArrayAccess {

    private $parameters = [];

    public function __construct($parameters = []) {
        $this->parameters = array_merge($this->parameters, $parameters);
    }


    public function get($key, $default = null) {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : $default;
    }

    /**
     * Sets a parameter by name.
     *
     * @param string $key The key
     * @param mixed $value The value
     */
    public function set($key, $value) {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key) {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     */
    public function remove($key) {
        unset($this->parameters[$key]);
    }

    public function all() {
        return $this->parameters;
    }

    public function load($parameters = []) {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }

    public function __get($name) {
        return $this->__isset($name) ? $this->parameters[$name] : NULL;
    }

    public function __set($name, $value) {
        $this->parameters[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->parameters[$name]);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {
        return $this->__isset($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {
        return $this->__get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {
        $this->__set($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        if ($this->__isset($offset)) {
            unset($this->parameters[$offset]);
        }
    }
}