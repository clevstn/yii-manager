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
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AppLogController extends CommonController
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/index/index');
    }
}