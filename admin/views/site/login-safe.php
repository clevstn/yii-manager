<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\admin\assets\SiteAsset;

/* @var $this \yii\web\View */
/* @var $param */
$this->title = '安全认证';
SiteAsset::register($this);
?>
<div class="container ym-safe" ng-controller="_loginSafeCtrl" ng-cloak>
    <div class="panel panel-default mt-50">
        <div class="panel-body p-24 row">
            <div class="col-sm-12 col-md-8 col-md-offset-2 min-300 px-0">
                <h4 class="text-center font-bold pb-16 underline">安全认证</h4>
                <div class="panel-body">
                    <div class="item-group">
                        <div class="item-group-row">
                            <span class="item-label">认证方式</span>
                            <div class="item-body">
                                邮件认证
                            </div>
                        </div>
                        <div class="item-group-row">
                            <span class="item-label">邮件验证码</span>
                            <div class="item-body">
                                <input type="text" class="form-control focus-define">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
