<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
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