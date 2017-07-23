<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 22:17
 */

namespace Joking\Kernel;


use Joking\Kernel\Support\Singleton;

class Test2 extends Singleton {

    public function q($name, Test $test) {
        return $name;
    }

}