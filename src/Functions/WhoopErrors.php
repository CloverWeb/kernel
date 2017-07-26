<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/23
 * Time: 20:40
 */

namespace Joking\Kernel\Functions;


use Joking\Kernel\Support\Singleton;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopErrors extends Singleton {

    public function register($debug = true) {
        $run = new Run();
        $run->pushHandler(new PrettyPageHandler());
        $run->register();
    }
}