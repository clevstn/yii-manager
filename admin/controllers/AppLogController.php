<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 应用日志记录
 * @author cleverstone
 * @since 1.0
 */
class AppLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 应用日志列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/index/index');
    }
}