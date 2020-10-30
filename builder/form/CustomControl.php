<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\form;

/**
 * 表单自定义项接口
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
interface CustomControl
{
    /**
     * 渲染自定义项
     *
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render();

    /**
     * 返回用于初始化自定义项值的Js函数。
     *
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function initValuesJsFunction();

    /**
     * 返回用于清空自定义项值的Js函数。
     *
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function clearValuesJsFunction();

    /**
     * 返回用于获取自定义项值的Js函数，Js函数返回值必须是一个Js `Object`。
     *
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getValuesJsFunction();
}