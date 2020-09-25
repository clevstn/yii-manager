<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/14
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 分页组件
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class LinkPager extends \yii\widgets\LinkPager
{
    /**
     * @var string|bool the label for the "next" page button. Note that this will NOT be HTML-encoded.
     * If this property is false, the "next" page button will not be displayed.
     */
    public $nextPageLabel = '<span aria-hidden="true" class="page-right glyphicon glyphicon-menu-right"></span>';
    /**
     * @var string|bool the text label for the "previous" page button. Note that this will NOT be HTML-encoded.
     * If this property is false, the "previous" page button will not be displayed.
     */
    public $prevPageLabel = '<span aria-hidden="true" class="page-left glyphicon glyphicon-menu-left"></span>';
    /**
     * @var bool Hide widget when only one page exist.
     */
    public $hideOnSinglePage = false;

    /**
     * @var array
     * @since 1.0
     */
    public $pageRowsMap = [
        '20' => '每页20条',
        '100' => '每页100条',
        '300' => '每页300条',
        '500' => '每页500条',
    ];

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        echo $this->renderPageButtons() . $this->renderPageRight();
    }

    /**
     * 渲染分页右侧统计和辅助功能
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function renderPageRight()
    {
        $currentPage = $this->pagination->getPage() + 1;
        $items[] = Html::tag(
            'li',
            "<span>到第</span>\n" . Html::tag(
                'span',
                Html::input(
                    'number',
                    'page-dump',
                    $currentPage,
                    [
                        'min' => 1,
                        'ng-model' => 'ymTableCurrentPage',
                        'ng-init' => 'ymTableCurrentPage=' . $currentPage,
                    ]
                ),
                ['class' => 'page-dump-control']
            ) . "\n<span>页</span>\n" . Html::a('确定', '#', [
                'class' => 'btn btn-sm btn-default',
                'ng-click' => 'ymTableDumpSpecialPage(' . $this->pagination->limit . ')',
            ])
        );

        $items[] = Html::tag('li', "<span>共{$this->pagination->totalCount}条</span>");
        $items[] = Html::tag(
            'li',
            Html::tag(
                'span',
                Html::tag('select', Html::renderSelectOptions($this->pagination->limit, $this->pageRowsMap), [
                    'id' => 'YmTablePageSelect',
                ]),
                ['class' => 'page-select-control']
            )
        );

        return Html::tag('ul', implode("\n", $items), ['class' => 'page-block']);
    }

    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     * @param string $label the text label for the button
     * @param int $page the page number
     * @param string $class the CSS class for the page button.
     * @param bool $disabled whether this page button is disabled
     * @param bool $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }

        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'span');

            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
        }

        $linkOptions = $this->linkOptions;
        $linkOptions['ng-click'] = 'ymTableDumpPage(' . ($page + 1) . ', ' . $this->pagination->limit . ')';

        return Html::tag($linkWrapTag, Html::a($label, '#', $linkOptions), $options);
    }
}