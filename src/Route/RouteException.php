<?php
/**
 * User: Administrator
 * Date: 2017/7/23
 * Time: 19:58
 */

namespace Joking\Kernel\Route;


use FastRoute\Dispatcher;
use Throwable;

class RouteException extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        if ($message == Dispatcher::NOT_FOUND) {
            $code = 404;            //404错误
            $message = '404 NOT FOUND';
        } else {
            $code = 405;            //405错误
            $message = '405 METHOD_NOT_ALLOWED';
        }

        parent::__construct($message, $code, $previous);
    }

}