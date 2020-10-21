<?php

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 手机区号
 * @author cleverstone
 * @since 1.0
 */
class AreaCodeController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'index',
    ];

    /**
     * 数据列表
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public function actionIndex()
    {
        return '';
    }
}