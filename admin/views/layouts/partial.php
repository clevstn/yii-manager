<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
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
        <meta name="author" content="hili">
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