<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 菜单
 * @author cleverstone
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