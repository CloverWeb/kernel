<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/22
 * Time: 0:31
 */

namespace Joking\Kernel\Route;


use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Joking\Kernel\Functions\Factory;

class FastRoute {

    protected $temporaryOptions = [];

    /**
     * @var RouteEntity[]
     */
    protected static $routeCollector = [];

    public function dispatch($url, $method) {
        $dispatcher = simpleDispatcher(function (RouteCollector $route) {
            foreach (static::$routeCollector as $url => $entity) {
                $route->addRoute($entity->method, $url, $entity);
            }
        });

        return $dispatcher->dispatch($method, $url);
    }

    /**
     * @param array $options ['namespace' , 'prefix' ， 'suffix' , 'middleware[]']
     * @param \Closure $callback
     */
    public function group($options = [], \Closure $callback) {
        isset($options['namespace']) && $options['namespace'] = $this->disposeNamespace($options['namespace']);
        isset($options['prefix']) && $options['prefix'] = $this->disposeUrl($options['prefix']);
        isset($options['middleware']) || $options['middleware'] = [];
        is_string($options['middleware']) && $options['middleware'] = [$options['middleware']];
        if (isset($this->temporaryOptions['middleware'])) {
            $options['middleware'] = array_merge($this->temporaryOptions['middleware'], $options['middleware']);
        }

        call_user_func($callback, Factory::instance(FastRoute::class, [], ['temporaryOptions' => $options]));
    }

    /**
     * @param $method
     * @param $url
     * @param $handle
     * @return RouteEntity
     */
    public function addRoute($method, $url, $handle) {
        if (is_string($handle) && substr($handle, 0, 1) === '\\') {
            $handle = trim($handle, '\\');
        }

        if (isset($this->temporaryOptions['namespace'])) {
            if (is_string($handle) && strpos($handle, '@')) {
                $handle = $this->temporaryOptions['namespace'] . $handle;
            }
        }

        $url = $this->disposeUrl($url);

        $routeArray = [
            'url' => $url,
            'method' => $method,
            'handle' => $handle,
            'middleware' => []
        ];

        if (isset($this->temporaryOptions['middleware'])) {
            $routeArray['middleware'] = array_merge($routeArray['middleware'], $this->temporaryOptions['middleware']);
        }

        /**
         * @var RouteEntity $routeEntity
         */
        $routeEntity = Factory::instance(RouteEntity::class, ['parameters' => $routeArray]);

        static::$routeCollector[$url] = $routeEntity;

        return $routeEntity;
    }

    public function get($url, $handle) {
        return $this->addRoute('GET', $url, $handle);
    }

    public function post($url, $handle) {
        return $this->addRoute('POST', $url, $handle);
    }

    public function delete($url, $handle) {
        return $this->addRoute('DELETE', $url, $handle);
    }

    public function put($url, $handle) {
        return $this->addRoute('PUT', $url, $handle);
    }

    protected function disposeUrl($url) {
        if (isset($this->temporaryOptions['prefix'])) {
            if (strpos($this->temporaryOptions['prefix'], '/') !== 0) {
                $this->temporaryOptions['prefix'] = '/' . $this->temporaryOptions['prefix'];
            }

            if (strpos($url, '/') !== 0 && substr($this->temporaryOptions['prefix'], -1) !== '/') {
                $url = '/' . $url;
            }

            $url = $this->temporaryOptions['prefix'] . $url;
        } else {
            strpos($url, '/') !== 0 && $url = '/' . $url;
        }

        if (isset($this->temporaryOptions['suffix'])) {
            $url = $url . $this->temporaryOptions['suffix'];
        }
        return $url;
    }

    protected function disposeNamespace($namespace) {
        if (substr($namespace, -1) !== '\\') {
            $namespace = $namespace . '\\';
        }

        if (isset($this->temporaryOptions['namespace'])) {
            if (substr($this->temporaryOptions['namespace'], -1) === '\\') {
                return $this->temporaryOptions['namespace'] . $namespace;
            } else {
                return $this->temporaryOptions['namespace'] . '\\' . $namespace;
            }
        }

        return $namespace;
    }
}