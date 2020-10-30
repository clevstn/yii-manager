<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

/* @var $this \yii\web\View */
/* @var $param */
$this->title = '404';
?>
<div style="display:table;width:100%;height:800px;user-select:none;-webkit-user-select:none;">
    <div style="display:table-cell;vertical-align:middle;text-align:center;">
        <img style="font-size:0;width:600px;" src="<?= Yii::getAlias('@web/media/image/404.png') ?>" alt>
        <div style="display:inline-block;width:230px;text-align:initial;vertical-align:middle;">
            <h1>404</h1>
            <p style="font-size:22px;line-height:42px;font-weight:400;">页面不存在</p>
            <p style="font-size:13px;line-height:18px;color:#999;">请检查您输入的网址是否正确，点击以下按钮返回主页或者发送错误报告</p>
            <a style="margin-top:35px;" class="btn btn-primary" href="<?= Yii::$app->homeUrl ?>">返回首页</a>
        </div>
    </div>
</div>

