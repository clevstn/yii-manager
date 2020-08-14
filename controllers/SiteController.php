<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * 网站首页
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
