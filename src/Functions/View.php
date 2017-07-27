<?php
/**
 * User: Administrator
 * Date: 2017/7/24
 * Time: 22:55
 */

namespace Joking\Kernel\Functions;


use Exception;
use Joking\Kernel\Functions\Interfaces\ViewMiddleware;
use Joking\Kernel\Support\Singleton;
use League\Plates\Engine;
use League\Plates\Template\Template;

/**
 * Class View
 * @property Engine templates
 * @package Joking\Kernel\Functions
 */
class View extends Singleton {

    //需要传入模板的参数
    protected $params = [];

    //视图的文件夹
    protected $folders = [];

    //视图中可以直接执行的方法
    protected $functions = [];

    //视图文件目录
    protected $mainFolder;

    //视图中间件 ['middleware' => ['template1' , 'template2'] ]
    protected $middleware = [];

    public function render($template, $params = []) {
        $this->multiple($params);
        foreach ($this->selectMiddleware($template) as $value) {
            if ($middlewareResult = Method::execute([$value, 'handle'], [$this])) {
                return $middlewareResult;
            }
        }

        return $this->templates->render($template, $this->params);
    }

    public function templateExists($template) {
        return $this->templates->exists($template);
    }

    public function with($name, $value) {
        $this->params[$name] = $value;
    }

    public function multiple($parameters = []) {
        $this->params = array_merge($this->params, $parameters);
    }

    public function registerFolders($name, $path) {
        $this->folders[$name] = $path;
    }

    public function registerMainFolders($path) {
        $this->mainFolder = $path;
    }

    public function registerFunction($functionName, \Closure $callback) {
        $this->functions[$functionName] = $callback;
    }

    public function registerMiddleware($template, $middleware) {
        $templates = is_array($template) ? $template : [$template];
        $this->middleware[$middleware] = $templates;
    }

    public function clean() {
        $this->params = [];
    }

    public function getTemplates() {
        if (isset($this->mainFolder)) {

            $templates = new Engine($this->mainFolder);

            foreach ($this->folders as $name => $folder) {
                $templates->addFolder($name, $folder);
            }

            foreach ($this->functions as $name => $callback) {
                $templates->registerFunction($name, $callback);
            }

            return $templates;
        }

        throw new \Exception('没有设置视图的主目录');
    }

    protected function selectMiddleware($template) {
        $middlewareArray = [];
        foreach ($this->middleware as $middleware => $templates) {
            foreach ($templates as $pattern) {
                if (strpos($pattern, '*') !== false) {
                    $pattern = '/^' . str_replace('*', '[\\S]', $pattern) . '/';
                    if (preg_match($pattern, $template)) {
                        $middlewareArray[] = $middleware;
                    }
                } else if ($template == $pattern) {
                    $middlewareArray[] = $middleware;
                }
            }
        }
        return $middlewareArray;
    }
}