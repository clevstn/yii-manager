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

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\builder\assets\MainAsset;
use app\builder\assets\CommonAsset;
use app\builder\helper\NavbarHelper;

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
<div class="ym-app">
    <!--头部导航-->
    <?php
    NavBar::begin([
        'options' => ['class' => 'navbar-default navbar-fixed-top ym-navbar-custom'],
        'headerContent' => NavbarHelper::renderToggleButton(),
        'innerContainerOptions' => ['class' => 'ym-inner-container'],
        'brandLabel' => $this->title ?: Yii::$app->params['adminTitle'],
        'brandUrl' => Url::current() ?: Yii::$app->params['adminUrl'],
        'brandOptions' => ['class' => 'ym-brand-mobile']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->adminUser->isGuest ? (
            ['label' => 'Login', 'url' => ['/admin/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/admin/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->adminUser->identity->username . ')',
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
    <aside class="ym-aside-menu">
        <div class="ym-brand-wrap">
            <a class="ym-brand-label" href="<?= Yii::$app->params['adminUrl'] ?>">
                <?= Yii::$app->params['adminTitle'] ?>
            </a>
        </div>
        <?=
        \app\builder\widgets\Menu::widget([
            'items' => [
                // Important: you need to specify url as 'controller/action',
                // not just as 'controller' even if default action is used.
                [
                    'label' => '仪表盘',
                    'url' => ['/admin/index/index'],
                ],
                // 'Products' menu item will be selected as long as the route is 'product/index'
                [
                    'label' => '会员管理',
                    'url' => '',
                    'items' => [
                        [
                            'label' => '会员列表',
                            'url' => ['/admin/site/test1', 'tag' => 'new'],
                        ],
                        [
                            'label' => '账户管理',
                            'url' => ['/admin/site/test2', 'tag' => 'popular'],
                        ],
                        [
                            'label' => '银行卡',
                            'url' => ['/admin/site/test3', 'tag' => 'popular'],
                        ],
                    ],
                ],
                [
                    'label' => '系统设置',
                    'url' => ['/admin/site/login'],
                    'visible' => Yii::$app->adminUser->isGuest,
                ],
            ],
        ]);
        ?>
    </aside>
    <main class="ym-content">
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
    <footer class="ym-footer">
        <div class="ym-inner-container">
            <p class="pull-left ym-copyright">&copy; <?= Yii::$app->name ?> <?= date('Y') ?></p>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

