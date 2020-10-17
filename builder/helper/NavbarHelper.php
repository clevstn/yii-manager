<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/20
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\helper;

use yii\bootstrap\Html;

/**
 * 导航栏助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class NavbarHelper
{
    /**
     * @var string text to show for screen readers for the button to toggle the navbar.
     */
    public $screenReaderToggleText = 'Toggle menu';

    /**
     * Renders left menu toggle button.
     * @return string the rendering toggle button.
     */
    public static function renderToggleButton()
    {
        $context = new static();
        $bar = Html::tag('span', '', ['class' => 'icon-bar']);
        $screenReader = "<span class=\"sr-only\">{$context->screenReaderToggleText}</span>";

        return Html::button("{$screenReader}\n{$bar}\n{$bar}\n{$bar}", [
            'class' => 'navbar-toggle ym-toggle-sidebar',
            'data-toggle' => 'sidebar',
            'data-target' => "#ym-sidebar",
        ]);
    }
}