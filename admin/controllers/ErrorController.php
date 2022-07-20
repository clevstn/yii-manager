<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\controllers;

use Yii;
use app\builder\common\CommonController;

/**
 * 错误页
 * @author HiLi
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