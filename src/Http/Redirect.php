<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 12:10
 */

namespace Joking\Kernel\Http;


use Joking\Kernel\Functions\UrlManager;
use Joking\Kernel\Support\Singleton;

class Redirect extends Singleton {

    protected $backName = '_backName';

    /**
     * 跳转至指定 url
     * @param $path
     * @param array $params
     */
    public function to($path, $params = []) {
        return $this->redirect(UrlManager::current()->to($path, $params));
    }

    public function cross($domain, $path, $params = []) {

    }

    /**
     * 跳转值某个已经定义的
     * @param $name
     * @param array $params
     */
    public function route($name, $params = []) {
        $url = UrlManager::current()->route($name, $params);
        return $this->redirect($url);
    }

    public function back() {
        exit('<script>history.go(-1)</script>');
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}