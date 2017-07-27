<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/27
 * Time: 20:02
 */

namespace Joking\Kernel\Functions\Facades;


use Joking\Kernel\Functions\UrlManager;
use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;

/**
 * Class Url
 * @method static string to($path, $params = [])
 * @method static string route($name, $params = [])
 * @package Joking\Kernel\Functions\Facades
 */
class Url extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return UrlManager::current();
    }
}