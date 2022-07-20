<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\contract;

use yii\base\Controller;

/**
 * 构建契约
 * @author HiLi
 * @since 1.0
 */
interface BuilderInterface
{
    /**
     * 渲染组件
     * @param Controller $context
     * @return string
     */
    public function render(Controller $context);
}