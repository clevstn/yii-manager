<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\controllers;

/**
 * 错误处理动作
 * @author cleverstone
 * @since ym1.0
 */
class ErrorController extends CommonController
{
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