<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use yii\helpers\Html;
use app\builder\assets\MainAsset;

/* @var $this \yii\web\View 视图组件实例 */
/* @var $content string     内容 */
MainAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="cleverstone">
        <meta name="robots" content="noindex, nofollow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Yii::$app->params['admin_title'] . ($this->title ? ' | ' . Html::encode($this->title) : '') ?></title>
        <?php $this->head() ?>
    </head>
    <!--这里必须是`_EasyApp`-->
    <body ng-app="_EasyApp">
    <?php $this->beginBody() ?>
    <!--content-->
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>