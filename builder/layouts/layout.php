<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\builder\assets\MainAsset;
use app\builder\helper\NavHelper;
use app\builder\helper\MenuHelper;
use app\builder\assets\CommonAsset;
use app\builder\helper\NavbarHelper;

/* @var $this \yii\web\View */
/* @var $content string */

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
    <!--navbar-->
    <?php
    NavBar::begin([
        'options' => ['class' => 'navbar-default navbar-fixed-top ym-navbar-custom'],
        'headerContent' => NavbarHelper::renderToggleButton(),
        'innerContainerOptions' => ['class' => 'ym-inner-container'],
        'brandLabel' => $this->title ?: Yii::$app->params['adminTitle'],
        'brandUrl' => Url::current() ?: Yii::$app->params['adminUrl'],
        'brandOptions' => ['class' => 'ym-brand-mobile']
    ]);
    echo NavHelper::renderItems();
    NavBar::end();
    ?>
    <!--asideBar-->
    <aside class="ym-aside-menu" id="ym-sidebar">
        <!--brand-->
        <div class="ym-brand-wrap">
            <a class="ym-brand-label" href="<?= Yii::$app->params['adminUrl'] ?>">
                <?= Yii::$app->params['adminTitle'] ?>
            </a>
            <button type="button" class="close ym-asidebar-close" aria-label="Close" data-toggle="sidebar"
                    data-target="#ym-sidebar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!--menu-->
        <?= MenuHelper::render() ?>
    </aside>
    <main class="ym-content">
        <!--breadcrubs-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="container-fluid">
            <!--content-->
            <?= $content ?>
        </div>
    </main>
    <!--footer-->
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

