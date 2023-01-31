<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 菜单
 * @author cleverstone
 * @since ym1.0
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