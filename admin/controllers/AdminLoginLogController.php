<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 管理员登录日志
 * @author HiLi
 * @since 1.0
 */
class AdminLoginLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 管理员登录日志列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/index/index');
    }
}