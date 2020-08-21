<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/19
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\widgets;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 菜单组件
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Menu extends \yii\widgets\Menu
{
    /**
     * 菜单id
     * @var string
     * @since 1.0
     */
    public $menuId = 'ym';

    /**
     * @var string 菜单模块class
     * @since 1.0
     */
    public $menuModuleClass = 'ym-submenu';

    /**
     * 菜单模块link模板
     * @var string
     * @since 1.0
     */
    public $menuModuleLinkTemplate = '<a class="ym-submenu-module{collapsed}" data-toggle="collapse" href="{id}">{icon}{label}</a>';

    /**
     * @var string 菜单项class
     * @since 1.0
     */
    public $menuItemClass = 'ym-menu-item';

    /**
     * 菜单项link模板
     * @var string
     * @since 1.0
     */
    public $linkTemplate = '<a class="ym-menu-item-link" href="{url}">{icon}{label}</a>';

    /**
     * 重写子菜单模板
     * @var string
     * @since 1.0
     */
    public $submenuTemplate = "\n<ul id=\"{id}\" class=\"ym-submenu-wrap {collapse}\">\n{items}\n</ul>\n";

    /**
     * @var string the template used to render the body of a menu which is NOT a link.
     * In this template, the token `{label}` will be replaced with the label of the menu item.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $labelTemplate = '<span>{icon}{label}</span>';

    /**
     * 重写选中样式
     * @var string
     * @since 1.0
     */
    public $activeCssClass = 'ym-active';

    /**
     * @var bool whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;

    /**
     * 重写根节点选项
     * @var array
     * @since 1.0
     */
    public $options = [
        'class' => 'ym-menu-wrap',
    ];

    /**
     * 重写render items
     * @param array $items
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        $targetId = ArrayHelper::remove($items, 'targetId');
        foreach ($items as $i => $item) {
            $item['targetId'] = $targetId ? "{$targetId}_{$i}" : "{$this->menuId}_{$i}";
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }

            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }

            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }

            Html::addCssClass($options, $class);

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                Html::addCssClass($options, [$this->menuModuleClass]);
                $activeMap = ArrayHelper::getColumn($item['items'], 'active');
                $activeSubmenu = false;
                if (!empty($activeMap) && array_search(true, $activeMap) !== false) {
                    $activeSubmenu = true;
                }

                $item['items']['targetId'] = $item['targetId'];
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $collapseClass = $activeSubmenu === true ? 'collapse in' : 'collapse';
                $menu = strtr($menu, [
                    '{collapsed}' => $activeSubmenu === true ? '' : ' collapsed',
                ]);

                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                    '{id}' => $item['targetId'],
                    '{collapse}' => $collapseClass
                ]);
            } else {
                Html::addCssClass($options, [$this->menuItemClass]);
            }

            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * 重写render item
     * @param array $item
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function renderItem($item)
    {
        $icon = '';
        if (isset($item['icon'])) {
            $icon = Html::tag('i', '', ['class' => [$item['icon'], 'ym-sidebar-icon']]);
        }

        if (isset($item['url']) || !empty($item['items'])) {
            $linkTemplate = !empty($item['items']) ? $this->menuModuleLinkTemplate : $this->linkTemplate;
            $template = ArrayHelper::getValue($item, 'template', $linkTemplate);

            return strtr($template, [
                '{url}' => isset($item['url']) ? Html::encode(Url::to($item['url'])) : '',
                '{label}' => $item['label'],
                '{id}' => "#{$item['targetId']}",
                '{icon}' => $icon,
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label'],
            '{icon}' => $icon,
        ]);
    }
}