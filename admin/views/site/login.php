<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\admin\assets\SiteAsset;

/* @var $this \yii\web\View */
/* @var $param */
$this->title = '登录';
SiteAsset::register($this);
?>
<div class="ym-login" ng-controller="_loginCtrl" ng-cloak>
    <div class="login-wrap">
        登录
    </div>
</div>
