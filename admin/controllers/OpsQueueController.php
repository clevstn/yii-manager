<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 队列监控
 * @author cleverstone
 * @since ym1.0
 */
class OpsQueueController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 队列监控列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}