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
    <form class="panel panel-default mt-50">
        <div class="panel-body p-24 pt-6 pb-0 row underline">
            <div class="col-sm-12 col-md-8 col-md-offset-2 min-300 px-0">
                <h4 class="text-center font-bold pb-16 pt-16">安全认证</h4>
                <div class="alert" ng-class="appSuccess ? 'alert-success' : 'alert-warning'" role="alert" ng-if="appInfo">
                    <i class="fa fa-exclamation-triangle text-warning" ng-if="!appSuccess"></i>
                    <i class="fa fa-check text-success" ng-if="appSuccess"></i>
                    <span ng-bind="appInfo"></span>
                </div>
                <div class="panel-body">
                    <div class="item-group">
                        <div class="item-group-row">
                            <span class="item-label">认证方式</span>
                            <div class="item-body">
                                <span><?= AdminUser::getIsSafeAuthLabel($this->params['ways']) ?></span>
                            </div>
                        </div>
                        <?php switch ($this->params['ways']): case AdminUser::SAFE_AUTH_EMAIL: // 邮箱认证?>
                        <div class="item-group-row">
                            <span class="item-label">邮件验证码</span>
                            <div class="item-body">
                                <label class="sr-only" for="email_code">Email verification code</label>
                                <input type="number" min="0" id="email_code" class="form-control focus-define inline-block w-auto" autocomplete="off" placeholder="请输入验证码">
                                <button type="button" class="btn btn-default ml-3" ng-click="getVerificationCode()" ng-bind="emailBtnLabel"></button>
                            </div>
                        </div>
                        <?php break; case AdminUser::SAFE_AUTH_MESSAGE: // 短信认证?>
                            <div class="item-group-row">
                                <span class="item-label">短信验证码</span>
                                <div class="item-body">
                                    <label class="sr-only" for="message_code">Message verification code</label>
                                    <input type="number" min="0" id="message_code" class="form-control focus-define inline-block w-auto" autocomplete="off" placeholder="请输入验证码">
                                    <button type="button" class="btn btn-default ml-3" ng-click="getMessageCode()" ng-bind="messageBtnLabel"></button>
                                </div>
                            </div>
                        <?php break; case AdminUser::SAFE_AUTH_OTP: // OTP认证?>
                            <div class="item-group-row">
                                <span class="item-label">OTP数字串</span>
                                <div class="item-body">
                                    <label class="sr-only" for="otp_code">OTP verification code</label>
                                    <input type="number" min="0" id="otp_code" class="form-control focus-define inline-block w-auto" autocomplete="off" placeholder="请输入OTP数字串">
                                </div>
                            </div>
                        <?php break; default: ?>
                        <div class="item-group-row">
                            <span class="item-label"></span>
                            <div class="item-body">未知认证方式</div>
                        </div>
                        <?php endswitch; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body p-24 row">
            <div class="col-xs-10 col-xs-offset-1 row">
                <button type="button" class="btn btn-default btn-lg col-xs-5 border-radius-none" ng-click="backLog()">切换其他账号</button>
                <button type="submit" class="btn btn-primary btn-lg col-xs-5 col-xs-offset-2 border-radius-none" ng-click="continueLog()">继续登录</button>
            </div>
        </div>
    </form>
</div>
