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
        <div class="panel-body">
            <div class="login-header">
                <img class="login-logo" src="/media/image/login-photo.jpg" alt>
                <p class="login-title">用户登录</p>
            </div>
        </div>
        <div class="panel-body">
            <form class="p-24">
                <div class="form-group">
                    <label class="sr-only" for="username">username</label>
                    <div class="input-group">
                        <div class="input-group-addon form-group-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <input type="text" class="form-control focus-define" id="username" name="username" placeholder="邮箱/用户名" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="password">password</label>
                    <div class="input-group">
                        <div class="input-group-addon form-group-icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <input type="password" class="form-control focus-define" id="password" name="password" placeholder="请输入登录密码" autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="next-step btn btn-primary" ng-click="loginSubmit()">登录</button>
            </form>
        </div>
        <div class="panel-body pt-0 top-line">
            <p class="other-loginway">其他登录方式</p>
            <div class="panel-body other-loginbody">
                <div class="way-item" ng-click="otherLgn('asm', $event)">
                    <img class="saom-login" src="/media/image/saom.png" alt>
                    <p>APP扫码</p>
                </div>
            </div>
        </div>
    </div>
</div>
