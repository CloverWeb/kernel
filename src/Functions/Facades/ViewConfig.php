<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 0:50
 */

namespace Joking\Kernel\Functions\Facades;


use Joking\Kernel\Functions\Interfaces\ViewMiddleware;
use Joking\Kernel\Support\Facades;
use Joking\Kernel\Support\Singleton;
use \Joking\Kernel\Functions\View as BaseView;

/**
 * Class ViewConfig
 *
 * @method static registerMainFolders(string $path)
 * @method static registerFolders($name, string $path)
 * @method static registerFunction($functionName, \Closure $callback)
 * @method static registerMiddleware($name, string $middleware)
 * @package Joking\Kernel\Functions\Facades
 */
class ViewConfig extends Facades {

    protected function getFacadeAccessor(): Singleton {
        return BaseView::current();
    }
}