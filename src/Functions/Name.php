<?php
/**
 * User: Administrator
 * Date: 2017/7/20
 * Time: 21:24
 */

namespace Joking\Kernel\Functions;


use Joking\Kernel\Support\Singleton;
use Joking\Kernel\Test;

class Name extends Singleton {

    protected $test;

    public function __construct(Test $test) {
        $this->test = $test;
    }

    public function haha($na = []) {
        return $this->test;
    }
}