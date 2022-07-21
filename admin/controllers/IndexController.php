<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 后台默认控制器
 * @author cleverstone
 * @since 1.0
 */
class IndexController extends CommonController
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
    public $undetectedActions = [
        'index',
    ];

    /**
     * 后台首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
