<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 22:14
 */

namespace Joking\Kernel\Http;

use Joking\Kernel\Functions\View;
use Joking\Kernel\Kernel;
use Joking\Kernel\Route\FastRoute;
use Joking\Kernel\Route\RouteException;
use Joking\Kernel\Support\Singleton;

class HttpResponse extends Singleton {

    const RESPONSE_HTML = 'text/html';
    const RESPONSE_JSON = 'application/json';
    const RESPONSE_XML = 'text/xml';
    const RESPONSE_DOWNLOAD = 'application/octet-stream';
    const RESPONSE_IMAGE = 'image/jpeg';

    protected $isSent = false;              //是否已经发送
    protected $content;             //需要输出的内容
    protected $format;              //响应格式
    protected $headers = [];             //需要输出的响应头
    protected $params = [];         //渲染模板需要

    public function with($name, $value) {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * @param $template
     * @param array $params
     * @return HttpResponse
     */
    public function view($template, $params = []) {
        $this->setRespondType(static::RESPONSE_HTML);
        $this->setStatusCode(200);
        $this->content = View::current()->render($template, array_merge($this->params, $params));
        return $this;
    }

    public function json($data = []) {
        $this->setRespondType(static::RESPONSE_JSON);
        $this->content = $data;
        return $this;
    }

    public function xml($data = []) {
        $this->setRespondType(static::RESPONSE_XML);
    }

    /**
     * 响应图片
     * @param string $file 一定要是全路径的喂
     * @return HttpResponse
     */
    public function image($file) {
        $this->setRespondType(static::RESPONSE_IMAGE);
        if (file_exists($file)) {
            $this->content = file_get_contents($file);
        } else {
            $this->content = $file;
        }
        return $this;
    }

    public function redirect($url) {

    }

    /**
     * 发送错误
     * @param $template
     * @param $status
     */
    public function error($template, $status) {

    }

    public function forward($routerName) {
        $fastRoute = new FastRoute();
        if ($entity = $fastRoute->getRoute($routerName)) {
            return Kernel::current()->handle($entity);
        }

        throw new RouteException(404);
    }

    /**
     * @param string $name 可选参数 ： RESPOND_HTML，RESPOND_JSON，RESPOND_XML
     */
    protected function setRespondType($name) {
        $this->addHeader('Content-Type:' . $name);
    }

    public function setStatusCode($status = 200) {
        $this->addHeader('HTTP/1.1 ' . $status);
    }

    public function setLanguage($language = 'zh-cn') {
        $this->addHeader('Content-language: ' . $language);
    }

    /**
     * 发送最终结果
     * @param HttpRequest $request
     * @return string
     */
    public function send(HttpRequest $request) {
        if (!$this->isSent) {
            $this->sendHeader();
            $this->isSent = true;
            return $this->disposeContent();
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * 添加响应头
     * @param $value
     * @return mixed
     */
    protected function addHeader($value) {
        return $this->headers[] = $value;
    }

    /**
     * 发送响应头
     */
    protected function sendHeader() {
        if (headers_sent()) {
            return;
        }

        foreach ($this->headers as $header) {
            header($header);
        }
    }

    protected function disposeContent() {
        $content = '';

        switch ($this->format) {
            case self::RESPONSE_HTML :
            case self::RESPONSE_IMAGE:
                $content = $this->content;
                break;
            case self::RESPONSE_JSON :
                $content = json_encode($this->content, JSON_UNESCAPED_UNICODE);
                break;
            case self::RESPONSE_XML:
                break;
            case self::RESPONSE_DOWNLOAD;
                break;
            default :
                if (is_string($this->content) || is_int($this->content)) {
                    $this->setRespondType(self::RESPONSE_HTML);
                    $this->setStatusCode(200);
                    return $this->content;
                }
        }

        return $content;
    }
}