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

/**
 * Class Response
 * @method static view($template, $params = [])
 * @method static json($data = [])
 * @method static xml($data = [])
 * @method static image($file)
 * @method static redirect($url, $params = [])
 * @method static forward($routerName)
 * @method static error($template, $status)
 * @package Joking\Kernel\Functions\Facades
 */
class Response extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return HttpResponse::current();
    }
}