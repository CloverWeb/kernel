<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 22:13
 */

namespace Joking\Kernel\Functions\Facades;


use Joking\Kernel\Http\HttpResponse;
use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;

class Response extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return HttpResponse::current();
    }
}