<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\contract;

use yii\base\Controller;

/**
 * 构建契约
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
interface BuilderInterface
{
    /**
     * 渲染组件
     * @param Controller $context
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render(Controller $context);
}