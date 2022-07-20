<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 系统设置
 * @author HiLi
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
     * 系统设置列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}