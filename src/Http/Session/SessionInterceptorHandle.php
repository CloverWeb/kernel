<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 21:14
 */

namespace Joking\Kernel\Http\Session;


interface SessionInterceptorHandle {

    /**
     * 是否拦截
     * @param $stdClass [handle , name , value]
     * @return bool true : 拦截 ，false ： 不拦截
     */
    public function isIntercept(\stdClass $stdClass);

    /**
     * 需要执行的东西
     * @param \stdClass $stdClass
     * @return void
     */
    public function handle(\stdClass $stdClass);
}