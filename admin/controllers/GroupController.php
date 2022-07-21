<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 管理组
 * @author cleverstone
 * @since 1.0
 */
class GroupController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 管理组列表
     * @return string
     */
    public function actionIndex()
    {
        return '';
    }
}