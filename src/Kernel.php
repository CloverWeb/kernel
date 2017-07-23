<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/20
 * Time: 21:42
 */

namespace Joking\Kernel;


use Joking\Kernel\Functions\Config;
use Joking\Kernel\Support\Singleton;

class Kernel extends Singleton {

    protected $defaultConfigFile = 'config/app.php';

    public function start($config) {

        //用户的配置项和默认配置项合并注册配置项
        $defaultConfig = include $this->defaultConfigFile;
        $config = array_merge($defaultConfig, $config);
        Config::current()->register($config);
    }

}