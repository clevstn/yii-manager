<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\builder\assets\MainAsset;
use app\builder\assets\CommonAsset;

// 公共依赖包
CommonAsset::register($this);
// 公共定义包
MainAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="author" content="cleverstone">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="app">
    <!--头部导航-->
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['adminTitle'],
        'brandUrl' => Yii::$app->params['adminUrl'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top navbar-custom',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/admin/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/admin/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
    <!--左侧菜单-->
    <aside class="aside-menu">
        <?=
        \yii\widgets\Menu::widget([
            'options' => ['class' => ''],
            'items' => [
                // Important: you need to specify url as 'controller/action',
                // not just as 'controller' even if default action is used.
                ['label' => 'Home', 'url' => ['site/index'], 'options' => ['class' => '']],
                // 'Products' menu item will be selected as long as the route is 'product/index'
                ['label' => 'Products', 'url' => ['product/index'], 'items' => [
                    ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
                    ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
                ], 'options' => [
                    'data-toggle' => 'collapse',
                ]],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
            ],
        ]);
        ?>
    </aside>
    <main class="content">
        <!--面包屑导航-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="container-fluid">
            <!--内容-->
            <?= $content ?>
        </div>
    </main>
    <!--尾部-->
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Yii::$app->name ?> <?= date('Y') ?></p>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

