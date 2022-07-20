<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\widgets;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 菜单组件
 * @author HiLi
 * @since 1.0
 */
class Menu extends \yii\widgets\Menu
{
    /**
     * @var string 菜单id
     */
    public $menuId = 'ym';

    /**
     * @var string 菜单模块class
     */
    public $menuModuleClass = 'ym-submenu';

    /**
     * @var string 菜单模块link模板
     */
    public $menuModuleLinkTemplate = '<a class="ym-submenu-module{collapsed}" data-toggle="collapse" href="{id}">{icon}{label}</a>';

    /**
     * @var string 菜单项class
     */
    public $menuItemClass = 'ym-menu-item';

    /**
     * {@inheritDoc}
     */
    public $linkTemplate = '<a class="ym-menu-item-link" href="{url}">{icon}{label}</a>';

    /**
     * {@inheritDoc}
     */
    public $submenuTemplate = "\n<ul id=\"{id}\" class=\"ym-submenu-wrap {collapse}\">\n{items}\n</ul>\n";

    /**
     * {@inheritDoc}
     */
    public $labelTemplate = '<span>{icon}{label}</span>';

    /**
     * @var string 重写选中样式
     */
    public $activeCssClass = 'ym-active';

    /**
     * @var bool whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;

    /**
     * @var array 重写根节点选项
     */
    public $options = [
        'class' => 'ym-menu-wrap',
    ];

    /**
     * 重写render items
     * @param array $items
     * @return string
     * @throws \Exception
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