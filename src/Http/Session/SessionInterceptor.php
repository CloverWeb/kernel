<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 21:11
 */

namespace Joking\Kernel\Http\Session;


use Joking\Kernel\Support\Singleton;

class SessionInterceptor extends Singleton {

    protected $intercepts = [];

    public function register($sessionInterceptor) {
        $sessionInterceptor = is_array($sessionInterceptor) ? $sessionInterceptor : [$sessionInterceptor];
        foreach ($sessionInterceptor as $value) {
            $this->intercepts[] = $value;
        }
    }

    public function getIntercepts() {
        return $this->intercepts;
    }
}