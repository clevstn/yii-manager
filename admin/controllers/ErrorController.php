<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

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
     * {@inheritdoc}
     */
    public $publicActions = ['error'];

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