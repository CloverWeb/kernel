<?php
/**
 * User: Administrator
 * Date: 2017/7/24
 * Time: 18:44
 */

namespace Joking\Kernel\Functions\Facades;


use Joking\Kernel\Http\HttpRequest;
use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;

/**
 * Class Request
 * @method static string method
 * @method static string uri
 * @method static string url
 * @method static string get($name, $default = null)
 * @method static string input($name, $default = null)
 * @method static string post($name, $default = null)
 * @method static bool isGet
 * @method static bool isPost
 * @method static bool isAjax
 * @method static string host
 * @method static string baseUri
 * @method static int port
 * @method static file
 * @package Joking\Kernel\Functions\Facades
 */
class Request extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return HttpRequest::current();
    }
}