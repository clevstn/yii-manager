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
use app\builder\assets\MainAsset;
use app\builder\helper\NavHelper;
use app\builder\helper\MenuHelper;
use app\builder\helper\NavbarHelper;

/* @var $this \yii\web\View */
/* @var $content string */

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
    <title><?= Yii::$app->params['admin_title'] . ($this->title ? ' | ' . Html::encode($this->title) : '') ?></title>
    <?php $this->head() ?>
</head>
<body ng-app="_EasyApp">
<?php $this->beginBody() ?>
<div class="ym-app">
    <!--Brand-->
    <script>
    /*console.log("%c@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@``````````.@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@``````````@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@```````````@@```@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@``````````@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@```````````@@```@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@``````````@@#``.@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@```.......:@@```@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@```@@@@@@@@@#``.@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@```@@@@@@@@@@```@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@```@@@@@@@@@#``.@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" +
        "@```@@@@@@@@@@```@#````````@:``#@@:``#@````````#@```@@@@```@@@@@@@@.`````@@````````@@````````@@````````#@\n" +
        "@```@@@@@@@@@@```@#````````#'``,@@```@@````````#@```;`,@``````````@``````@@````````@@````````@@````````#@\n" +
        "@```@@@@@@@@@@```@#````````#@```@@```@@````````#@`````,@``````````@``````@@````````@@````````@@````````#@\n" +
        "@```@@@@@@@@@@```@#``.@@```#@```@'``,@@```@@```#@`````,@``````````@:.```.@@```..```@@```..```@@```@@```#@\n" +
        "@```@@@@@@@@@@```@#````````#@,``;.``+@@````````#@````;@@.......```@@#``.@@@```@@```@@```@@```@@````````#@\n" +
        "@```@@@@@@@@@@```@#````````#@#``.```@@@````````#@```@@@@@@@@@@@```@@#``.@@@```@@```@@```@@```@@````````#@\n" +
        "@```@@@@@@@@@@```@#````````#@@``````@@@````````#@```@@@@@@@@@@@```@@#``.@@@```@@```@@```@@```@@````````#@\n" +
        "@```.......:@@```@#``.@@@@@@@@.````;@@@```@@@@@@@```@@@@.......```@@#``.@@@```@@```@@```@@```@@```@@@@@@@\n" +
        "@```````````@@```@#````````@@@;````@@@@````````#@```@@@@``````````@@#````@@````````@@```@@```@@````````#@\n" +
        "@```````````@@```@#````````#@@@````@@@@````````#@```@@@@``````````@@#````@@````````@@```@@```@@````````#@\n" +
        "@``````````.@@```@#````````#@@@```,@@@@````````#@```@@@@``````````@@#````@@````````@@```@@```@@````````#@\n" +
        "@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@", "color:#337ab7;font-size:12px;");*/

    </script>

    <!--navbar-->
    <?php
    NavBar::begin([
        'options' => ['class' => 'navbar-default navbar-fixed-top ym-navbar-custom'],
        'headerContent' => NavbarHelper::renderToggleButton(),
        'innerContainerOptions' => ['class' => 'ym-inner-container'],
        'brandLabel' => $this->title ?: Yii::$app->params['admin_title'],
        'brandUrl' => Url::current() ?: Yii::$app->params['admin_url'],
        'brandOptions' => ['class' => 'ym-brand-mobile']
    ]);
    echo NavHelper::renderItems();
    NavBar::end();
    ?>

    <!--asideBar-->
    <aside class="ym-aside-menu" id="ym-sidebar">
        <!--brand-->
        <div class="ym-brand-wrap">
            <a class="ym-brand-label" href="<?= Yii::$app->params['admin_url'] ?>">
                <?= Yii::$app->params['admin_title'] ?>
            </a>
            <button type="button" class="close ym-asidebar-close" aria-label="Close" data-toggle="sidebar"
                    data-target="#ym-sidebar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!--menu-->
        <?= MenuHelper::render() ?>
    </aside>

    <main class="ym-content" ng-cloak>
        <!--content-->
        <div class="container-fluid ym-content-fluid">
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

