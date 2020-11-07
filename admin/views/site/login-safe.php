<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\models\AdminUser;
use app\admin\assets\SiteAsset;

/* @var $this \yii\web\View */
$this->title = '安全认证';
SiteAsset::register($this);
?>
<div class="container ym-safe" ng-controller="_loginSafeCtrl" ng-cloak>
    <div class="panel panel-default mt-50">
        <div class="panel-body p-24 pt-6 pb-0 row underline">
            <div class="col-sm-12 col-md-8 col-md-offset-2 min-300 px-0">
                <h4 class="text-center font-bold pb-16">安全认证</h4>
                <div class="panel-body">
                    <div class="item-group">
                        <div class="item-group-row">
                            <span class="item-label">认证方式</span>
                            <div class="item-body">
                                <span><?= AdminUser::getIsSafeAuthLabel($this->params['ways']) ?></span>
                            </div>
                        </div>
                        <div class="item-group-row">
                            <span class="item-label">邮件验证码</span>
                            <div class="item-body item-merge">
                                <input type="text" class="form-control focus-define inline-block w-auto">
                                <button type="button" class="btn btn-default ml-3">获取验证码</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body p-24 row">
            <div class="col-xs-10 col-xs-offset-1 row">
                <button type="button" class="btn btn-default btn-lg col-xs-5 border-radius-none" ng-click="backLog()">切换其他账号</button>
                <button type="button" class="btn btn-primary btn-lg col-xs-5 col-xs-offset-2 border-radius-none" ng-click="continueLog()">继续登录</button>
            </div>
        </div>
    </div>
</div>
