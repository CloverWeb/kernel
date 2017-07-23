<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/23
 * Time: 1:59
 */

namespace Joking\Kernel\Functions;


use Joking\Kernel\Support\Singleton;

class Config extends Singleton {

    protected $config = ['name' => [
        'class' => [
            'haha' => 'this is haha'
        ]
    ], 'sss' => 222];

    public function get($name, $default = null) {
        $array = [];
        strpos($name, '.') ? $array = explode('.', $name) : $array[] = $name;
        $config = $this->config;
        for ($i = 0; $i < count($array); $i++) {
            if (is_array($config) && isset($config[$array[$i]])) {
                $config = $config[$array[$i]];
            } else {
                return $default;
            }
        }

        return $config;
    }

    public function set() {

    }

    public function register($config) {
        $this->config = array_merge($this->config, $config);
    }
}