<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 20:38
 */

namespace Joking\Kernel\Http\Session;


class SessionHandler {

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null) {
        return $this->has($name) ? $_SESSION[$name]->value : $default;
    }

    public function add($name, $value, $timeout = 0) {
        $timeout = $timeout > 0 ? time() + $timeout : 0;
        $_SESSION[$name] = new SessionEntity(['name' => $name, 'value' => $value, 'timeout' => $timeout]);
    }

    public function remove($name) {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * 判断session是否存在
     * @param $name
     * @return bool
     */
    public function has($name) {
        if (isset($_SESSION[$name])) {
            if ($_SESSION[$name] instanceof SessionEntity) {
                if (!$this->checkTimeout($_SESSION[$name])) {
                    return true;
                } else {
                    $this->remove($name);
                }
            }
        }

        return false;
    }

    /**
     * 判断 session 是否依然坚挺
     * 当时间 $entity->timeout 为 0 时那么该session值坚挺到海枯石烂
     * @param SessionEntity $entity
     * @return bool
     */
    protected function checkTimeout(SessionEntity $entity) {
        if ($entity->timeout > 0) {
            return $entity ? $entity->timeout > time() ? false : true : true;
        } else {
            return false;
        }
    }
}