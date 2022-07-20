<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\api\v2\controllers;

use app\api\RestController;

/**
 * 默认接口
 * @author HiLi
 * @since 1.0
 */
class IndexController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
