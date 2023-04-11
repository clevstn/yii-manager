<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\widgets;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 菜单组件
 * @author cleverstone
 * @since ym1.0
 */
class Menu extends \yii\widgets\Menu
{
    /**
     * @var string 节点ID
     */
    public $nodeId = 'ym';

    /**
     * @var string 父级Ui class
     */
    public $parentUiClass = 'ym-submenu';

    /**
     * @var string 父级link模板
     */
    public $parentLinkTemplate = '<a class="ym-submenu-module{collapsed}" data-toggle="collapse" href="{id}">{icon}{label}</a>';

    /**
     * @var string 菜单项Ui class
     */
    public $itemUiClass = 'ym-menu-item';

    /**
     * {@inheritDoc}
     */
    public $linkTemplate = '<a class="ym-menu-item-link" href="{url}" target="{target}">{icon}{label}</a>';

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
            $item['targetId'] = $targetId ? "{$targetId}_{$i}" : "{$this->nodeId}_{$i}";
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
                Html::addCssClass($options, [$this->parentUiClass]);
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
                Html::addCssClass($options, [$this->itemUiClass]);
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
            $linkTemplate = !empty($item['items']) ? $this->parentLinkTemplate : $this->linkTemplate;
            $template = ArrayHelper::getValue($item, 'template', $linkTemplate);

            return strtr($template, [
                '{url}' => isset($item['url']) ? Html::encode(Url::to($item['src'], '')) : '',
                '{label}' => $item['label'],
                '{id}' => "#{$item['targetId']}",
                '{target}' => !empty($item['dump_way']) ? $item['dump_way'] : '_self',
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