<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\OtpBindAsset;

/* @var $this \yii\web\View */
/* @var array $param 视图参数*/
/* @var string $titleName 标题*/
/* @var string $qrcodeUrl 二维码链接*/
/* @var string $accountName 账号名*/
/* @var string $googleKey 密匙*/
OtpBindAsset::register($this);

$this->title = 'MFA绑定';
?>
<div class="panel panel-default" ng-controller="_otpBindCtrl">
    <?php if(!empty($titleName)): ?>
    <div class="panel-heading border-bottom clearfix">
        <h4 class="f-13"><?= $titleName ?></h4>
    </div>
    <?php endif; ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 col-md-offset-3 pt-16">
                <img class="img-thumbnail" src="<?= $qrcodeUrl ?>" alt>
            </div>
            <div class="col-md-3">
                <div class="caption">
                    <h3 class="pt-16">安装教程</h3>
                    <hr/>
                    <p class="f-13 pb-16 text-info">1、下载阿里云、腾讯云、Google Authenticator、FreeOTP等支持MFA动态码的手机软件，建议下载阿里云或腾讯云即可；</p>
                    <p class="f-13 pb-16 text-info">2、打开并注册软件，进入【MFA工具】，点击【添加】，选择【扫码加入】；</p>
                    <p class="f-13 text-info">3、添加成功后，即可使用MFA动态密码。</p>
                    <hr/>
                    <p class="f-14 text-danger">注：如需打开MFA认证，请前往【后台管理】->【系统设置】->【后台配置】->选择【2FA】中【OTP认证】</p>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-3 col-md-offset-7">
                <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" ng-click="viewUserSecret()">查看账号名和密匙</button>
            </div>
        </div>
    </div>
</div>

<div id="_viewUserAndSecret" style="display:none;">
    <div class="col-md-12">
        <div class="panel-body">
            <table class="layui-table">
                <colgroup>
                    <col width="300">
                    <col width="300">
                </colgroup>
                <thead>
                <tr>
                    <th>账号名</th>
                    <th>密匙</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <p class="text-gray"><?= $accountName ?></p>
                    </td>
                    <td>
                        <p class="text-gray"><?= $googleKey ?></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
