<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\api\v1\controllers;

use app\api\RestController;

/**
 * 错误页
 * @author cleverstone
 * @since ym1.0
 */
class ErrorController extends RestController
{
    /**
     * {@inheritdoc}
     */
    public $guestActions = ['error'];

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