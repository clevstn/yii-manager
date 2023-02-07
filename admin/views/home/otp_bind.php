<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\OtpBindAsset;

/* @var $this \yii\web\View */
/* @var $param */
/* @var string $qrcodeUrl */
OtpBindAsset::register($this);

$this->title = 'OTP绑定';
?>
<div class="panel panel-default" ng-controller="_otpBindCtrl">
    <div class="panel-heading border-bottom clearfix">
        <h4 class="f-13">OTP绑定</h4>
    </div>
    <div class="panel-body">
        <img src="<?= $qrcodeUrl ?>" alt>
    </div>
</div>
