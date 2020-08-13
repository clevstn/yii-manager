<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/6
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 错误页
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ErrorController extends CommonController
{

    /**
     * Guest actions defined
     *
     * @var array
     */
    public $guestActions = ['error'];

    /**
     * Register undetected action ids for RBAC
     *
     * @var array
     */
    public $undetectedActions = ['error'];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'index',
            ]
        ];
    }
}