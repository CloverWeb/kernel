<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 23:14
 */

namespace Joking\Kernel\Functions;


class FunctionParameters {

    /**
     * @param \ReflectionMethod|\ReflectionFunction $reflectionFunction
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public static function get($reflectionFunction, $params = []) {
        $reflectionParameters = $reflectionFunction->getParameters();

        $temporaryArray = [];
        $functionParams = [];
        static::isNumberArray($params) && $functionParams = $params;

        for ($i = count($functionParams); $i < count($reflectionParameters); $i++) {
            if (array_key_exists($reflectionParameters[$i]->getName(), $params)) {
                $temporaryArray[] = $params[$reflectionParameters[$i]->getName()];
                continue;
            } else if ($reflectionParameters[$i]->hasType() && $reflectionParameters[$i]->getClass() != null) {
                $temporaryArray[] = Factory::instance(
                    $reflectionParameters[$i]->getClass()->getName()
                );
                continue;
            } else if ($reflectionParameters[$i]->isDefaultValueAvailable()) {
                $temporaryArray[] = $reflectionParameters[$i]->getDefaultValue();
                continue;
            }

            throw new \Exception('没有找到参数：' . $reflectionParameters[$i]->getName());
        }

        foreach ($temporaryArray as $value) {
            array_push($functionParams, $value);
        }
        return $functionParams;
    }

    /**
     * 判断数组是否是键为数字的数组
     * ['a','b'] 这样的返回 true
     * ['name' => 'value'] 这样的返回 false
     * @param $array
     * @return bool
     */
    protected static function isNumberArray($array) {
        if (empty($array)) {
            return false;
        }

        foreach (array_keys($array) as $key) {
            if (!is_numeric($key)) {
                return false;
            }
        }
        return true;
    }

}