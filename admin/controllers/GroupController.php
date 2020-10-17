<?php

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
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [];

    /**
     * @author cleverstone
     * @since 1.0
     */
    public function actionIndex()
    {
        return '';
    }
}