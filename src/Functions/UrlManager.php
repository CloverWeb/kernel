<?php
/**
 * User: Administrator
 * Date: 2017/7/26
 * Time: 23:01
 */

namespace Joking\Kernel\Functions;


use Joking\Kernel\Support\Singleton;

class UrlManager extends Singleton {

    public function to($path, $params = []) {
        $paramString = $this->formatParams($params);
        $paramString = empty($paramString) ? '' : '?' . $paramString;
        return '/' . trim($path, '&/?\'') . $paramString;
    }

    public function route($name, $params) {

    }

    protected function formatParams($params = []) {
        if (empty($params)) {
            return '';
        }

        $strArray = [];
        foreach ($params as $name => $value) {
            $strArray[] = $name . '=' . $value;
        }

        return implode('&&', $strArray);
    }
}