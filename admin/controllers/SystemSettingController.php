<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 系统设置
 * @author cleverstone
 * @since ym1.0
 */
class SystemSettingController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get', 'post'],
    ];

    /**
     * 系统设置列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}