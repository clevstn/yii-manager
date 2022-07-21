<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\admin\controllers;

use Yii;
use app\builder\common\CommonController;

/**
 * 错误页
 * @author cleverstone
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
        if (Yii::$app->adminUser->isGuest) {
            $this->layout = 'partial';
        }

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'index',
            ]
        ];
    }
}