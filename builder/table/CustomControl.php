<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

/**
 * 自定义筛选控件接口
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
interface CustomControl
{
    /**
     * 渲染方法
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render();

    /**
     * 返回用于获取值的Js脚本
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getJsValues();

    /**
     * 返回清空值的Js脚本
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function clearJsValues();

    /**
     * 返回用于初始化值的Js脚本
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function initJsValues();
}