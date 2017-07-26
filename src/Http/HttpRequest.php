<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/28
 * Time: 12:42
 */

namespace Joking\Kernel\Http;


use Joking\Kernel\Functions\Factory;
use Joking\Kernel\Support\Singleton;

class HttpRequest extends Singleton {

    public function __construct(HttpServer $server) {
        $this->server = $server;
        $this->session = HttpSession::current();
    }

    /**
     * @var HttpServer
     */
    public $server;

    /**
     * @var HttpSession
     */
    public $session;

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function input($name, $default = null) {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    public function get($name, $default = null) {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    public function post($name, $default = null) {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    public function isGet() {
        return $this->server->get('REQUEST_METHOD') === 'GET';
    }

    public function isPost() {
        return $this->server->get('REQUEST_METHOD') === 'POST';
    }

    public function isAjax() {
        return $this->server->has('HTTP_X_REQUESTED_WITH') && strtolower($this->server->get('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    public function method() {
        return $this->server->get('REQUEST_METHOD');
    }

    public function host() {
        return $this->server->get('HTTP_HOST');
    }

    public function uri() {
        return $this->server->get('REQUEST_URI');
    }

    public function url() {
        $httpType = (($this->server->has('HTTPS') && $this->server->get('HTTPS') == 'on')
            || ($this->server->has('HTTP_X_FORWARDED_PROTO') && $this->server->get('HTTP_X_FORWARDED_PROTO') == 'https'))
            ? 'https://' : 'http://';

        return $httpType . $this->host() . $this->uri();
    }

    //基本路径不带参数的
    public function baseUri() {
        return $this->server->get('PHP_SELF');
    }

    public function port() {
        return $this->server->get('SERVER_PORT');
    }

    public function file($name = null) {
        return $name ? $_FILES[$name] : $_FILES;
    }
}