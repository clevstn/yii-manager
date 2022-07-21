<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 运维脚本
 * @author cleverstone
 * @since 1.0
 */
class OpsScriptController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 运维脚本列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}