<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/11
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 系统设置
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
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
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionIndex()
    {
        return $this->render('/index/index');
    }
}