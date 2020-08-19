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
     * 重写link模板
     * @var string
     * @since 1.0
     */
    public $linkTemplate = '<a class="ym-menu-item-link" href="{url}">{label}</a>';

    /**
     * 重写子菜单模板
     * @var string
     * @since 1.0
     */
    public $submenuTemplate = "\n<ul id=\"{id}\" class=\"ym-submenu-wrap collapse\">\n{items}\n</ul>\n";

    /**
     * 重写选中样式
     * @var string
     * @since 1.0
     */
    public $activeCssClass = 'ym-active';

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
            $item['targetId'] = $targetId ? "{$targetId}_{$i}" : "ym_{$i}";
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
                $item['items']['targetId'] = $item['targetId'];
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                    '{id}' => $item['targetId'],
                ]);
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
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
                '{id}' => "#{$item['targetId']}",
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label'],
        ]);
    }
}