<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

use Yii;
use yii\web\View;
use yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\Linkable;
use yii\base\BaseObject;
use yii\base\Controller;
use yii\helpers\ArrayHelper;
use app\builder\widgets\LinkPager;
use yii\base\NotSupportedException;
use app\builder\contract\BuilderInterface;
use app\builder\contract\NotFoundAttributeException;

/**
 * 表格构建器
 *
 * @property string $title
 * @property boolean $page
 * @property array $columns
 * @property \Closure $query
 * @property boolean $partial
 * @property array $rowActions
 * @property-read array $toolbars
 * @property string|Widget $widget
 * @property array|string $orderBy
 * @property boolean $hideCheckbox
 * @property array $checkboxOptions
 * @property array|string $primaryKey
 * @property-write array $toolbarRefresh
 * @property-write array $toolbarFilter
 * @property-write array $toolbarExport
 * @property-write array $toolbarCustom
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Builder extends BaseObject implements BuilderInterface
{
    /**
     * 表格标题
     * @var string
     * @since 1.0
     * @see $title
     * @see setTitle()
     */
    private $_title = '';

    /**
     * 表格列
     * @var array
     * - title string 列名
     * - options array
     *   - style string 样式
     *   - attribute string 属性
     * - callback null|\Closure 自定义闭包，用于自定义字段值
     * @since 1.0
     * @see $columns
     * @see table_column_helper()
     * @see setColumns()
     */
    private $_columns = [];

    /**
     * 查询器实例
     * @var \Closure
     * @since 1.0
     * @see $query
     * @see setQuery()
     */
    private $_query;

    /**
     * 主键
     * @var string|array
     * @since 1.0
     * @see $primaryKey
     * @see setPrimaryKey()
     */
    private $_primaryKey = 'id';

    /**
     * 定义Query排序
     * @var array
     * @since 1.0
     * @see $orderBy
     * @see setOrderBy()
     */
    private $_orderBy = ['id' => SORT_DESC];

    /**
     * 是否分页
     * @since 1.0
     * @see $page
     * @see setPage()
     */
    private $_page = true;

    /**
     * 是否隐藏多选框
     * @var bool
     * @since 1.0
     * @see $hideCheckbox
     * @see setHideCheckbox()
     */
    private $_hideCheckbox = false;

    /**
     * 定义多选选项
     * @var array
     * - style string 样式
     * - attribute string 属性
     * @since 1.0
     * @see $checkboxOptions
     * @see setCheckboxOptions()
     */
    private $_checkboxOptions = [
        'style' => 'width:50px;',
        'attribute' => '',
    ];

    /**
     * 设置行操作项
     * @var array
     * - type string 支持的值：`page`、`modal`、`ajax`、`division`
     *      - page      页面打开
     *      - modal     模态框打开
     *      - ajax      XMLHttpRequest访问
     *      - division  操作项分割线
     *
     * - options array 支持的选项：`title`、`icon`、`route`、`params`、`method`、`width`、`height`
     *      - title     操作项、`page`、`modal`标题
     *      - icon      操作项图标
     *      - route     路由
     *      - params    路由参数
     *      - method    访问动作，ajax类型有效
     *      - width     当前type为modal时有效，指定modal的宽，默认500px
     *      - height    当前type为modal时有效，指定modal的高，默认500px
     * @since 1.0
     * @see $rowActions
     * @see table_action_helper()
     * @see setRowActions()
     */
    private $_rowActions = [];

    /**
     * 表格数据
     * @var array
     * @since 1.0
     * @see resolveQuery()
     */
    private $_data = [];

    /**
     * 分页实例
     * @var Linkable|null
     * @since 1.0
     * @see resolveQuery()
     */
    private $_pagination;

    /**
     * 视图组件实例
     * @var View
     * @since 1.0
     * @see registerView()
     */
    private $_view;

    /**
     * 是否为局部视图
     * @var bool
     * @since 1.0
     * @see $partial
     */
    private $_partial = false;

    /**
     * 自定义组件
     * @var array
     * @since 1.0
     */
    private $_widget = [];

    /**
     * 工具栏
     * @var array
     * ```php
     * // 数据结构：
     * // - 支持的key有：`left`、`right`
     * // - 支持的type有：`custom`、`refresh`、`filter`、`export`
     * $toolbars = [
     *      'left' => [
     *          // 自定义
     *          ['type' => 'custom', ...]
     *      ],
     *      'right' => [
     *          // 刷新
     *          ['type' => 'refresh', 'title' => '', 'icon' => ''],
     *          // 筛选
     *          [
     *              'type' => 'filter',
     *              'title' => '',
     *              'icon' => '',
     *              // control支持的类型有：text、number、textarea、range、checkbox、radio、datetime、date、time、custom
     *              'columns' => [
     *                  'keyword' => [
     *                      'control' => 'text',
     *                      'default' => 1,
     *                      'style' => '',
     *                      'attribute' => '',
     *                  ],
     *                  //'order_num' => [
     *                  //    'control' => 'custom',
     *                  //    'widget'  => Object,
     *                  //],
     *                  'status' => [
     *                      'control' => 'select',
     *                      'default' => 1,
     *                      'options' => [],
     *                      'style' => '',
     *                      'attribute' => '',
     *                  ],
     *              ],
     *          ],
     *          // 导出
     *          ['type' => 'export', ...],
     *          // 自定义
     *          ['type' => 'custom', ...],
     *      ],
     * ]
     *
     * ```
     * @since 1.0
     * @see $toolbars
     * @see getToolbars()
     * @see $toolbarRefresh
     * @see setToolbarRefresh()
     * @see $toolbarFilter
     * @see setToolbarFilter()
     * @see $toolbarExport
     * @see setToolbarExport()
     * @see $toolbarCustom
     * @see setToolbarCustom()
     */
    private $_toolbars = [];

    /**
     * 局部视图路径
     * @var string
     * @since 1.0
     * @see $partial
     * @see render()
     */
    public $layoutPartial = '@builder/layouts/partial.php';

    /**
     * 模板路径
     * @var string
     * @since 1.0
     */
    private $_viewPath = '@builder/table/views/index.php';

    /**
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        $this->registerView();
    }

    /**
     * 设置表格名
     * @param string $title
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setTitle($title = '')
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * 获取表格名
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * 设置数据列
     * @param array $columns
     * @return $this
     * @throws NotSupportedException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setColumns(array $columns)
    {
        $defaultStyle = 'min-width:80px';
        foreach ($columns as $key => $item) {
            // single
            if (is_int($key)) {
                if (is_string($item)) {
                    $this->_columns[$item] = [
                        'title' => $item,
                        'options' => [
                            'style' => $defaultStyle,
                            'attribute' => '',
                        ],
                        'callback' => null,
                    ];
                }

                throw new NotSupportedException('The columns item is not supported. ');
            } else {
                // resolve options
                if (!empty($item['options'])) {
                    if (!empty($item['options']['style'])) {
                        if (is_array($item['options']['style'])) {
                            $item['options']['style'] = Html::cssStyleFromArray($item['options']['style']) ?: '';
                        }
                    } else {
                        $item['options']['style'] = $defaultStyle;
                    }

                    if (!empty($item['options']['attribute'])) {
                        if (is_array($item['options']['attribute'])) {
                            $item['options']['attribute'] = Html::renderTagAttributes($item['options']['attribute']);
                        }
                    } else {
                        $item['options']['attribute'] = '';
                    }

                } else {
                    $item['options']['style'] = $defaultStyle;
                    $item['options']['attribute'] = '';
                }

                $this->_columns[$key] = $item;
            }
        }

        return $this;
    }

    /**
     * 获取数据列
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * 设置query
     * @param \Closure $query
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setQuery(\Closure $query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     * 获取query
     * @return \Closure
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * 设置主键
     * @param string|array $field
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setPrimaryKey($field = 'id')
    {
        $this->_primaryKey = $field;
        return $this;
    }

    /**
     * 获取主键
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * 设置Query排序
     * @param array|string $orderBy
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setOrderBy($orderBy)
    {
        $this->_orderBy = $orderBy;
        return $this;
    }

    /**
     * 获取Query排序
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getOrderBy()
    {
        return $this->_orderBy;
    }

    /**
     * 设置是否分页
     * @param boolean $page
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setPage($page = true)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * 获取是否分页
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * 设置隐藏多选框
     * @param bool $isHide
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setHideCheckbox($isHide = true)
    {
        $this->_hideCheckbox = $isHide;
        return $this;
    }

    /**
     * 获取是否隐藏多选框
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getHideCheckbox()
    {
        return $this->_hideCheckbox;
    }

    /**
     * 设置多选框options
     * @param array $options
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setCheckboxOptions(array $options)
    {
        if (!empty($options['style'])) {
            if (is_array($options['style'])) {
                $cssOptions = Html::cssStyleFromArray($options['style']) ?: '';
            } else {
                $cssOptions = $options['style'];
            }

            $this->_checkboxOptions['style'] = $cssOptions;
        }

        if (!empty($options['attribute'])) {
            if (is_array($options['attribute'])) {
                $attributeOptions = Html::renderTagAttributes($options['attribute']);
            } else {
                $attributeOptions = $options['attribute'];
            }

            $this->_checkboxOptions['attribute'] = $attributeOptions;
        }

        return $this;
    }

    /**
     * 获取多选框options
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getCheckboxOptions()
    {
        return $this->_checkboxOptions;
    }

    /**
     * 设置行操作项
     * @param array $actions
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setRowActions(array $actions)
    {
        $this->_rowActions = $actions;
        return $this;
    }

    /**
     * 获取行情操作项
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getRowActions()
    {
        foreach ($this->_rowActions as &$item) {
            if (empty($item['options']['params'])) {
                $item['options']['params'] = (array)$this->primaryKey;
            }
        }

        return $this->_rowActions;
    }

    /**
     * 设置局部视图
     * @param bool $partial
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setPartial($partial = true)
    {
        $this->_partial = $partial ?: false;
        return $this;
    }

    /**
     * 获取是否是局部视图
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getPartial()
    {
        return $this->_partial;
    }

    /**
     * 设置组件
     * @param string|Widget $widget
     * @param int $pos
     * - TABLE_TOOL_TOP
     * - TABLE_TOOL_BOTTOM
     * - TABLE_PAGE_TOP
     * - TABLE_PAGE_BOTTOM
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setWidget($widget, $pos = Table::TABLE_TOOL_TOP)
    {
        if (is_array($widget)) {
            foreach ($widget as $i => $item) {
                $this->_widget[$i][] = $item;
            }
        } else {
            $this->_widget[$pos][] = $widget;
        }

        return $this;
    }

    /**
     * 获取组件
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getWidget()
    {
        return $this->_widget;
    }

    /**
     * 设置工具栏刷新
     * @param array $options
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setToolbarRefresh(array $options)
    {
        $options['type'] = 'refresh';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏筛选
     * @param array $options
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setToolbarFilter(array $options)
    {
        $options['type'] = 'filter';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏导出
     * @param array $options
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setToolbarExport(array $options)
    {
        $options['type'] = 'export';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏自定义项
     * @param array $options
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setToolbarCustom(array $options)
    {
        foreach ($options as $item) {
            $pos = ArrayHelper::remove($item, 'pos', 'left');
            $this->_toolbars[$pos][] = $item;
        }

        return $this;
    }

    /**
     * 获取工具栏
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getToolbars()
    {
        return $this->_toolbars;
    }

    /**
     * 渲染表格
     * @param Controller $context
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render(Controller $context)
    {
        if (Yii::$app->request->isAjax || accept_json()) {
            return $this->renderAjax($context);
        } else {
            $oldLayout = $context->layout;
            if ($this->partial === true) {
                $context->layout = $this->layoutPartial;
            }

            $viewBody = $this->renderHtml($context);
            $context->layout = $oldLayout;

            return $viewBody;
        }
    }

    /**
     * Ajax渲染
     * @param Controller $context
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function renderAjax(Controller $context)
    {
        $this->resolveQuery();

        return Json::encode([
            'data' => $this->_data,
            'page' => $this->page ? LinkPager::widget([
                'pagination' => $this->_pagination,
            ]) : '',
        ]);
    }

    /**
     * html渲染
     * @param Controller $context
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function renderHtml(Controller $context)
    {
        // Set table title
        $this->_view->title = $this->title;
        // Register the table widget script js
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        return $context->render($this->_viewPath, [
            'columns'           => $this->columns,
            'hideCheckbox'      => $this->hideCheckbox,
            'checkboxOptions'   => $this->checkboxOptions,
            'rowActions'        => $this->rowActions,
            'widgets'           => $this->widget,
            'toolbars'          => $this->toolbars,
        ]);
    }

    /**
     * 解析Query
     * @return $this
     * @throws NotFoundAttributeException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function resolveQuery()
    {
        /* @var \yii\db\QueryInterface $query */
        $query = call_user_func($this->query);
        if ($this->page === true) {
            @list($pages, $models) = resolve_pages($query, $this->orderBy);
        } else {
            $pages = null;
            $models = $query->orderBy($this->orderBy)->all();
        }

        $this->_pagination = $pages;

        foreach ($models as $item) {
            /* @var \yii\db\ActiveRecord $item */
            $item = $item->toArray();
            foreach ($this->columns as $field => $options) {
                if (!empty($options['callback']) && is_callable($options['callback'])) {
                    $value = call_user_func($options['callback'], $item);
                } elseif (isset($item[$field])) {
                    $value = html_escape($item[$field]);
                } else {
                    $value = '';
                }

                $item[$field] = $value;
            }

            $this->_data[] = $item;
        }

        return $this;
    }

    /**
     * 解析表格组件JS脚本
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function resolveJsScript()
    {
        return $this->_view->renderPhpFile(__DIR__ . '/app.js', [
            'link' => Url::toRoute('/' . Yii::$app->controller->route),
        ]);
    }

    /**
     * 注册视图组件实例
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
        return $this;
    }
}