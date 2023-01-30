<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\SiteAsset;

/* @var $this \yii\web\View */
/* @var $param */
$this->title = t('sign in');
SiteAsset::register($this);
?>
<div class="ym-login" ng-controller="_loginBaseCtrl" ng-cloak>
    <div class="login-wrap">
        <div class="panel-body">
            <div class="login-header">
                <img class="login-logo" ng-src="{{ loginPhoto }}" alt>
                <p class="login-title"><?= Yii::$app->params['admin_title'] . t('sign in') ?></p>
            </div>
        </div>
        <div class="panel-body">
            <form class="p-24 pt-0">
                <div class="alert alert-warning" role="alert" ng-if="loginErr">
                    <i class="fa fa-exclamation-triangle text-warning"></i>
                    <span ng-bind="loginErr"></span>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="username">username</label>
                    <div class="input-group">
                        <div class="input-group-addon form-group-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <input type="text" class="form-control focus-define" id="usernameOrEmail" name="usernameOrEmail" placeholder="<?= t('the email/username') ?>" autocomplete="off" ng-model="usernameOrEmail" ng-blur="checkUser()">
                    </div>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="password">password</label>
                    <div class="input-group">
                        <div class="input-group-addon form-group-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <input type="password" class="form-control focus-define" id="password" name="password" placeholder="<?= t('please enter your login password') ?>" autocomplete="new-password" ng-model="password">
                    </div>
                </div>
                <button type="submit" class="next-step btn btn-primary" ng-click="loginSubmit()"><?= t('sign in') ?></button>
            </form>
        </div>

        <!--其他登录方式-->
        <!--<div class="panel-body pt-0 top-line">
            <p class="other-loginway"><?= t('other login methods') ?></p>
            <div class="panel-body other-loginbody">
                <div class="way-item" ng-click="otherLgn('asm', $event)">
                    <img class="saom-login" src="/media/image/saom.png" alt>
                    <p><?= t('APP code') ?></p>
                </div>
            </div>
        </div>-->

    </div>
</div>
