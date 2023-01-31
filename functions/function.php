<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/**
 * 建议用户自定义的函数在这里编写
 * @since ym1.0
 */

if (!function_exists('is_function')) {
    /**
     * 判断一个变量是否为函数
     * @param string|object $func 变量
     * @return bool
     */
    function is_function($func) {
        return (is_string($func) && function_exists($func)) || (is_object($func) && ($func instanceof \Closure));
    }
}