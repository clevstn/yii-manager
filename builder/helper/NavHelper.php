<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\helper;

use Yii;
use yii\helpers\Html;
use yii\bootstrap\Nav;

/**
 * 导航助手
 * @author cleverstone
 * @since 1.0
 */
class NavHelper
{
    /**
     * 导航左侧项
     * @return string
     * @throws \Exception
     */
    public static function renderLeftItems()
    {
        $context = new static();
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items' => $context->getItems()['left'],
        ]);
    }

    /**
     * 导航右侧项
     * @return string
     * @throws \Exception
     */
    public static function renderRightItems()
    {
        $context = new static();
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $context->getItems()['right'],
        ]);
    }

    /**
     * 我的品牌
     * @return string
     */
    public static function getMyBrand()
    {
        $adminUser = Yii::$app->adminUser;
        $label = Html::tag('span', t('current') . ': ' . ($adminUser->isGuest ? t('empty') : $adminUser->identity->username), [
            'style' => ['margin-top' => '12px']
        ]);
        $headSrc = $adminUser->isGuest ? '@web/media/image/head.png' : attach_url($adminUser->identity->photo);
        $headContent = Html::img($headSrc, [
            'style' => ['max-width' => '80%', 'height' => '70px'],
        ]);

        return Html::tag(
            'li',
            "{$headContent}\n{$label}",
            [
                'class' => 'dropdown-header custom-header',
            ]
        );
    }

    /**
     * 获取导航项
     * @return array
     */
    protected function getItems()
    {
        $navPath = Yii::$app->basePath . '/config/nav.php';
        return include($navPath);
    }
}