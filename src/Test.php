<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 22:15
 */

namespace Joking\Kernel;


use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;

class Test extends Facades {

    protected $methodMap = [
        'lala' => 'q'
    ];

    protected function getFacadeAccessor(): Singleton {
        return Test2::current();
    }
}