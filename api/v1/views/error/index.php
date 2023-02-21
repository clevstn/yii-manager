<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/* @var $this \yii\web\View */
/* @var string $name 网关错误*/
/* @var string $message 网关错误消息*/
/* @var \Throwable $exception */
$this->title = !empty($name) ? $name : '系统错误';
?>
<div style="display:table;width:100%;height:800px;user-select:none;-webkit-user-select:none;">
    <div style="display:table-cell;vertical-align:middle;text-align:center;">
        <img style="font-size:0;width:600px;" src="<?= Yii::getAlias('@web/media/image/error.png') ?>" alt>
        <div style="display:inline-block;width:230px;text-align:initial;vertical-align:middle;">
            <h1><?= Yii::$app->response->getStatusCode() ?></h1>
            <p style="font-size:22px;line-height:42px;font-weight:400;"><?= !empty($name) ? $name : '系统错误' ?></p>
            <p style="font-size:13px;line-height:18px;color:#999;"><?= !empty($message) ? $message : '请求检查网址是否正确或联系平台管理员!' ?></p>
            <a style="margin-top:35px;" class="btn btn-primary" href="<?= Yii::$app->homeUrl ?>">返回首页</a>
        </div>
    </div>
</div>