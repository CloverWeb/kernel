<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25
 * Time: 23:29
 */

namespace Joking\Kernel\Functions\Interfaces;


use Joking\Kernel\Functions\View;

interface ViewMiddleware {

    public function handle(View $view);

}