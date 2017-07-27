<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/27
 * Time: 21:01
 */

namespace Joking\Kernel\Functions\Facades;


use Joking\Kernel\Http\HttpSession;
use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;

/**
 * Class Session
 * @method static mixed get($name, $default = null)
 * @method static void add($name, $value, $timeout = 0)
 * @method static void remove($name)
 * @method static void setSessionName($name)
 * @method static string getSessionName
 * @method static void setId($id)
 * @method static string getId
 * @method static void registerInterceptor($sessionInterceptor)
 * @package Joking\Kernel\Functions\Facades
 */
class Session extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return HttpSession::current();
    }
}