<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\controllers;

use yii\web\Controller;

/**
 * 网站首页
 * @author HiLi
 * @since 1.0
 */
class SiteController extends Controller
{
    /**
     * Displays homepage.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
