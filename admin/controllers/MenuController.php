<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 菜单
 * @author hili
 * @since 1.0
 */
class MenuController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 菜单列表
     * @return string
     */
    public function actionIndex()
    {
        return '';
    }
}