<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\table;

use Yii;
use yii\web\View;
use yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\Linkable;
use app\extend\Extend;
use yii\base\BaseObject;
use yii\base\Controller;
use yii\helpers\ArrayHelper;
use app\builder\widgets\LinkPager;
use yii\base\NotSupportedException;
use yii\base\InvalidArgumentException;
use app\builder\contract\BuilderInterface;
use app\builder\contract\UndefinedOptionsException;
use app\builder\contract\NotFoundAttributeException;

/**
 * 表格构建器
 * @property string $title   表格头部标题
 * @property boolean $page   是否设置分页
 * @property array $columns  表格列
 * @property \Closure $query 查询器实例或数据数组
 * @property-write array $js 设置额外的Js代码
 * @property-write array $css 设置额外的Css代码
 * @property boolean $partial 是否独立布局；该配置也可通过get参数__partial__进行设置
 * @property array $rowActions 设置行操作项
 * @property-read array $toolbars 设置工具栏
 * @property string|Widget $widget 自定义组件
 * @property array|string $orderBy 数据排序
 * @property boolean $hideCheckbox 是否隐藏多选框
 * @property array $checkboxOptions 定义多选选项Css样式
 * @property-write array $assetBundle 设置额外的Asset包
 * @property array|string $primaryKey 定义表格主键
 * @property-write array $toolbarRefresh 定义表格工具项刷新
 * @property-write array $toolbarFilter  定义表格工具项筛选
 * @property-write array $toolbarExport  定义表格工具项调出
 * @property-write array $toolbarCustom  自定义表格工具项
 * @author cleverstone
 * @since ym1.0
 */
class Builder extends BaseObject implements BuilderInterface
{

    const PER_ROW = 5000;

    /**
     * @var string 表格标题
     * @see $title
     * @see setTitle()
     */
    private $_title = '';

    /**
     * @var array 表格列
     * - title string 列名
     * - options array
     *   - style string 样式
     *   - attribute string 属性
     * - callback null|\Closure 自定义闭包，用于自定义字段值
     *
     * @see $columns
     * @see table_column_helper()
     * @see setColumns()
     */
    private $_columns = [];

    /**
     * @var \Closure|array 查询器实例或数据数组
     * @see $query
     * @see setQuery()
     */
    private $_query;

    /**
     * @var string|array 主键
     * @see $primaryKey
     * @see setPrimaryKey()
     */
    private $_primaryKey = 'id';

    /**
     * @var array 定义Query排序
     * @see $orderBy
     * @see setOrderBy()
     */
    private $_orderBy = ['id' => SORT_DESC];

    /**
     * @var bool 是否分页
     * @see $page
     * @see setPage()
     */
    private $_page = true;

    /**
     * @var bool 是否隐藏多选框
     * @see $hideCheckbox
     * @see setHideCheckbox()
     */
    private $_hideCheckbox = false;

    /**
     * @var array 定义多选选项
     * - style string 样式
     * - attribute string 属性
     *
     * @see $checkboxOptions
     * @see setCheckboxOptions()
     */
    private $_checkboxOptions = [
        'style' => 'width:50px;',
        'attribute' => '',
    ];

    /**
     * @var array 设置行操作项
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
     *      - width     当前type为modal时有效，指定modal的宽，默认800px
     *      - height    当前type为modal时有效，指定modal的高，默认520px
     *      - actionId 操作ID,用于动态展示操作项,需要在`columns`中定义是否展示,返回true则显示, 返回false则隐藏; 注意: 该值必须可以作为js变量
     *
     * @see $rowActions
     * @see table_action_helper()
     * @see setRowActions()
     */
    private $_rowActions = [];

    /**
     * 行操作项ID
     * @var array
     * @see $rowActions
     * @see table_action_helper()
     * @see setRowActions()
     */
    private $_rowActionIds = [];

    /**
     * @var array 表格数据
     * @see resolveQuery()
     */
    private $_data = [];

    /**
     * @var Linkable|null 分页实例
     * @see resolveQuery()
     */
    private $_pagination;

    /**
     * @var View 视图组件实例
     * @see registerView()
     */
    private $_view;

    /**
     * @var bool 是否为局部视图
     * @see $partial
     */
    private $_partial = false;

    /**
     * @var array 工具栏
     * ```php
     * // 数据结构：
     * // - 支持的key有：`left`、`right`
     * // - 支持的type有：`custom`、`refresh`、`filter`、`export`
     * $toolbars = [
     *      'left' => [
     *          // 自定义 @see ToolbarCustomOptions
     *          ['type' => 'custom', 'title' => '', 'icon' => '', ...]
     *      ],
     *      'right' => [
     *          // 刷新
     *          ['type' => 'refresh', 'title' => '', 'icon' => ''],
     *          // 筛选
     *          ['type' => 'filter', 'title' => '', 'icon' => ''],
     *          // 导出
     *          ['type' => 'export', 'title' => '', 'icon' => ''],
     *
     *          // 自定义 @see ToolbarCustomOptions
     *          ['type' => 'custom', 'title' => '', 'icon' => '', ...],
     *      ],
     * ]
     * ```
     *
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
     * @var array 筛选表单字段项
     *  control支持的类型有:
     *  - text
     *  - select
     *  - number
     *  - datetime
     *  - date
     *  - year
     *  - month
     *  - time
     *  - custom
     * ```php
     *  $columns = [
     *              'keyword' => [
     *                  'control' => 'text',
     *                  'label' => '关键词',
     *                  'placeholder' => '请填写关键词',
     *                  'default' => 1,
     *                  'style' => '',
     *                  'attribute' => '',
     *              ],
     *              //'order_num' => [
     *                  //    'control' => 'custom',
     *                  //    'widget'  => Object,
     *              //],
     *              'status' => [
     *                  'control' => 'select',
     *                  'label' => '状态',
     *                  'placeholder' => '请选择状态',
     *                  'default' => 1,
     *                  'options' => [],
     *                  'style' => '',
     *                  'attribute' => '',
     *              ],
     *         ],
     * ```
     */
    private $_filterColumns = [];

    /**
     * @var bool 导出标识
     */
    private $_exportFlag = false;

    /**
     * @var array 数据导出选项
     * - heads 自定义头部
     *    ['ID', '用户名', '邮箱', '电话']
     * - fields 自定义字段; 如果没定义则使用列表字段
     *   ['id', 'username', 'email', 'an', 'mobile']
     * - columns 自定义列
     *  [
     *      'id',
     *      'username',
     *      'email',
     *      'mobile' => function ($item) {
     *      return '+' . $item['an'] . ' ' . $item['mobile'];
     *      },
     *  ],
     */
    private $_exportOptions = [];

    /**
     * @var string 独立视图路径
     * @see $partial
     * @see render()
     */
    public $layoutPartial = '@builder/layouts/partial.php';

    /**
     * @var string Yii-manager layouts.
     */
    public $layoutPath = '@builder/layouts/layout.php';

    /**
     * @var string 模板路径
     */
    private $_viewPath = '@builder/table/views/index.php';

    /**
     * @var array 自定义组件
     */
    private $_widget = [];

    /**
     * @var array Asset包定义
     */
    private $_assetBundle = [];

    /**
     * @var array 额外的Js代码
     */
    private $_js = [];

    /**
     * @var array 额外的css代码
     */
    private $_css = [];

    /**
     * 初始化构建器
     */
    public function init()
    {
        $this->registerView();
    }

    /**
     * 设置表格名
     * @param string $title 表格页头标题
     * @return $this
     */
    public function setTitle($title = '')
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * 获取表格名
     * @return string
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

                    continue;
                }

                throw new NotSupportedException('The columns item data type is not supported. Only support string type!');
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
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * 设置query
     * @param \Closure $query
     * @return $this
     */
    public function setQuery(\Closure $query)
    {
        $this->_query = $query;
        return $this;
    }

    /**
     * 获取query
     * @return \Closure
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * 设置主键
     * @param string|array $field
     * @return $this
     */
    public function setPrimaryKey($field = 'id')
    {
        $this->_primaryKey = $field;
        return $this;
    }

    /**
     * 获取主键
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * 设置Query排序
     * @param array|string $orderBy
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->_orderBy = $orderBy;
        return $this;
    }

    /**
     * 获取Query排序
     * @return array
     */
    public function getOrderBy()
    {
        return $this->_orderBy;
    }

    /**
     * 设置是否分页
     * @param boolean $page
     * @return $this
     */
    public function setPage($page = true)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * 获取是否分页
     * @return bool
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * 设置隐藏多选框
     * @param bool $isHide
     * @return $this
     */
    public function setHideCheckbox($isHide = true)
    {
        $this->_hideCheckbox = $isHide;
        return $this;
    }

    /**
     * 获取是否隐藏多选框
     * @return bool
     */
    public function getHideCheckbox()
    {
        return $this->_hideCheckbox;
    }

    /**
     * 设置多选框options
     * @param array $options
     * @return $this
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
     */
    public function getCheckboxOptions()
    {
        return $this->_checkboxOptions;
    }

    /**
     * 设置行操作项
     * @param array $actions
     * @return $this
     */
    public function setRowActions(array $actions)
    {
        $rowActionIds = [];
        foreach ($actions as $key => $item) {
            $options = !empty($item['options']) ? $item['options'] : [];
            if (!empty($options['actionId'])) {
                array_push($rowActionIds, $options['actionId']);
            }

            if (isset($options['_isRender']) && !$options['_isRender']) {
                unset($actions[$key]);
            }
        }

        $this->_rowActionIds = $rowActionIds;
        $this->_rowActions = $actions;

        return $this;
    }

    /**
     * 获取行情操作项
     * @return array
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
     */
    public function setPartial($partial = true)
    {
        $this->_partial = $partial ?: false;
        return $this;
    }

    /**
     * 获取是否是局部视图
     * @return bool
     */
    public function getPartial()
    {
        return $this->_partial;
    }

    /**
     * 设置组件
     * @param string|array|Widget $widget
     * @param int $pos
     * - TABLE_TOOL_TOP
     * - TABLE_TOOL_BOTTOM
     * - TABLE_PAGE_TOP
     * - TABLE_PAGE_BOTTOM
     *
     * @return $this
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
     * 注册额外的assetBundle
     * @param array|string $assetBundle
     * @return $this
     */
    public function setAssetBundle($assetBundle)
    {
        $assetBundle = (array)$assetBundle;
        foreach ($assetBundle as $in) {
            $this->_assetBundle[] = $in;
        }

        return $this;
    }

    /**
     * 注册额外的Js代码
     * @param array|string $js
     * @param string $pos
     * @return $this
     */
    public function setJs($js, $pos = Table::JS_SCRIPT_INNER)
    {
        $js = (array)$js;
        foreach ($js as $in) {
            $this->_js[$pos][] = $in . "\n";
        }

        return $this;
    }

    /**
     * 注册额外的Css代码
     * @param array|string $css
     * @return $this
     */
    public function setCss($css)
    {
        $css = (array)$css;
        foreach ($css as $in) {
            $this->_css[] = $in . "\n";
        }

        return $this;
    }

    /**
     * 获取组件
     * @return array
     */
    public function getWidget()
    {
        return $this->_widget;
    }

    /**
     * 设置工具栏刷新
     * @param array $options
     * @return $this
     */
    public function setToolbarRefresh(array $options = [])
    {
        $options['type'] = 'refresh';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏筛选
     * @param array $options
     * - title
     * - icon
     * - columns
     *
     * @return $this
     * @see $_filterColumns
     * @see ToolbarFilterOptions
     */
    public function setToolbarFilter(array $options)
    {
        if (empty($options['columns'])) {
            throw new InvalidArgumentException('Option missing parameters `columns`. ');
        }

        $this->_filterColumns = ArrayHelper::remove($options, 'columns', []);
        $options['type'] = 'filter';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏导出
     * @param array $options
     * @return $this
     * @throws UndefinedOptionsException
     * @see $_exportOptions
     */
    public function setToolbarExport(array $options)
    {
        $this->_exportOptions['heads']      = ArrayHelper::remove($options, 'heads', []);
        $this->_exportOptions['fields']     = ArrayHelper::remove($options, 'fields', []);
        $this->_exportOptions['columns']    = ArrayHelper::remove($options, 'columns', []);
        if (empty($this->_exportOptions['heads'])) {
            throw new UndefinedOptionsException('Option missing parameters `heads`. ');
        }

        $this->_exportOptions['name']       = ArrayHelper::remove($options, 'name', 'export_default');
        $this->_exportFlag = true;

        $options['type'] = 'export';
        $this->_toolbars['right'][] = $options;

        return $this;
    }

    /**
     * 设置工具栏自定义项
     * @param array $options
     * @return $this
     */
    public function setToolbarCustom(array $options)
    {
        // 去除空数组
        $options = array_filter($options);

        foreach ($options as $item) {
            $pos = ArrayHelper::remove($item, 'pos', 'left');
            $item['type'] = 'custom';
            $this->_toolbars[$pos][] = $item;
        }

        return $this;
    }

    /**
     * 获取工具栏
     * @return array
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
     */
    public function render(Controller $context)
    {
        if (Yii::$app->request->isAjax || accept_json()) {
            /* @var int $exportFlag  Data export flag */
            if (Yii::$app->request->getQueryParam('__export')) {
                // ajax export
                return $this->renderExport($context);
            } else {
                // ajax list
                return $this->renderAjax($context);
            }
        } else {
            if (Yii::$app->request->getQueryParam('__export')) {
                // export data
                return $this->exportData();
            } else {
                // render list
                $oldLayout = $context->layout;
                if ($this->partial === true) {
                    // 独立布局
                    $context->layout = $this->layoutPartial;
                } else {
                    $partial = Yii::$app->request->get('__partial__', 0);
                    if ($partial) {
                        // 独立布局
                        $context->layout = $this->layoutPartial;
                    } else {
                        // 默认布局
                        $context->layout = $this->layoutPath;
                    }
                }

                $viewBody = $this->renderHtml($context);
                $context->layout = $oldLayout;

                return $viewBody;
            }
        }
    }

    /**
     * 数据导出
     * @throws \Exception
     */
    protected function exportData()
    {
        set_time_limit(0);
        ini_set('memory_limit', '500M');

        $queryParams = Yii::$app->request->get();
        $filename = Yii::$app->request->getQueryParam('_filename', 'export_default_' . time());

        /* @var array|\yii\db\Query $query */
        $query = call_user_func($this->query);

        $offset = isset($queryParams['_offset']) ? $queryParams['_offset'] : 0;
        $limit = isset($queryParams['_limit']) ? $queryParams['_limit'] : self::PER_ROW;

        if (is_object($query) && $query instanceof \yii\db\QueryInterface) {
            // the query is db query instance

            /* Reset select */
            if (!empty($this->_exportOptions['fields'])) {
                $query->select($this->_exportOptions['fields']);
            }

            $all = $query->offset($offset)
                ->limit($limit)
                ->orderBy($this->orderBy)
                ->all();
        } else {
            // the query is array
            $all = array_slice($query, $offset, $limit);
        }

        $dataMap = [];
        foreach ($all as $item) {
            if (empty($this->_exportOptions['columns'])) {
                // empty
                /* @var \yii\base\Model $item */
                if ($item instanceof \yii\base\Model) {
                    $item = $item->toArray();
                }

                array_push($dataMap, $item);
            } else {
                // not empty
                $tempMap = [];
                foreach ($this->_exportOptions['columns'] as $i => $col) {
                    if (is_int($i)) {
                        if (isset($item[$col])) {
                            $tempMap[$col] = $item[$col];
                        } else {
                            $tempMap[$col] = '--';
                        }
                    } elseif (is_callable($col)) {
                        $tempMap[$i] = call_user_func($col, $item, $all);
                    } else {
                        $tempMap[$i] = $col;
                    }
                }

                array_push($dataMap, $tempMap);
            }
        }

        $titleMap = $this->_exportOptions['heads'];
        Extend::spreadsheet()->export($titleMap, $dataMap, $filename);
    }

    /**
     * 数据导出渲染
     * @param Controller $context
     * @return string
     * @throws \Exception
     */
    protected function renderExport(Controller $context)
    {
        $exportGroup = $this->resolveExport($context);
        return Json::encode($exportGroup);
    }

    /**
     * Ajax渲染
     * @param Controller $context
     * @return string
     * @throws \Throwable
     */
    protected function renderAjax(Controller $context)
    {
        $this->resolveQuery();

        $page = '';
        if (
            $this->page
            && is_object($this->_pagination)
            && $this->_pagination instanceof \yii\web\Linkable
        ) {
            $page = LinkPager::widget([
                'pagination' => $this->_pagination,
            ]);
        }

        $data = [
            'data' => $this->_data,
        ];

        if (!empty($page)) {
            $data['page'] = $page;
        }
        return Json::encode($data);
    }

    /**
     * html渲染
     * @param Controller $context
     * @return string
     * @throws \Throwable
     */
    protected function renderHtml(Controller $context)
    {
        // 设置标题
        $this->_view->title = $this->title;

        // 注册Js,位于表格脚本上方
        if (!empty($this->_js[Table::JS_SCRIPT_TOP])) {
            $scriptTopJs = $this->_js[Table::JS_SCRIPT_TOP];
            foreach ($scriptTopJs as $topJs) {
                $this->_view->registerJs($topJs, View::POS_END);
            }
        }

        // 注册表格Js脚本
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        // 注册Js,位于表格脚本下方
        if (!empty($this->_js[Table::JS_SCRIPT_BOTTOM])) {
            $scriptBottomJs = $this->_js[Table::JS_SCRIPT_BOTTOM];
            foreach ($scriptBottomJs as $bottomJs) {
                $this->_view->registerJs($bottomJs, View::POS_END);
            }
        }

        // 注册Css脚本
        if (!empty($this->_css)) {
            foreach ($this->_css as $css) {
                $this->_view->registerCss($css);
            }
        }

        // 注册AssetBundle
        if (!empty($this->_assetBundle)) {
            foreach ($this->_assetBundle as $assetBundle) {
                if (class_exists($assetBundle)) {
                    $this->_view->registerAssetBundle($assetBundle);
                }
            }
        }

        return $context->render($this->_viewPath, [
            'columns'           => $this->columns,
            'hideCheckbox'      => $this->hideCheckbox,
            'checkboxOptions'   => $this->checkboxOptions,
            'rowActions'        => $this->rowActions,
            'rowActionIds'      => $this->_rowActionIds,
            'widgets'           => $this->widget,
            'toolbars'          => $this->toolbars,
            'filterColumns'     => $this->_filterColumns,
            'exportFlag'        => $this->_exportFlag,
        ]);
    }

    /**
     * 解析数据导出
     * @param Controller $context
     * @return array
     */
    public function resolveExport(Controller $context)
    {
        /* 每页5000条 */
        $perRow = self::PER_ROW;
        /* @var array|\yii\db\QueryInterface $query */
        $query = call_user_func($this->query);
        if (is_object($query) && $query instanceof \yii\db\QueryInterface) {
            $count = $query->count();
        } else {
            $count = count($query);
        }

        $totalPage = ceil($count / $perRow);
        $data = [];

        $filename = !empty($this->_exportOptions['name']) ? $this->_exportOptions['name'] : 'default';
        for ($i = 0; $i < $totalPage; $i++) {
            array_push($data, [
                'offset'    => ($i * $perRow),
                'limit'     => $perRow,
                'page'      => ($i + 1),
                'rows'      => ($count - ($i * $perRow)) > $perRow ? $perRow : ($count - ($i * $perRow)),
                'filename'  => $filename . '_chunk' . ($i + 1),
            ]);
        }

        return $data;
    }

    /**
     * 解析Query
     * @return $this
     * @throws NotFoundAttributeException
     */
    public function resolveQuery()
    {
        /* @var \yii\db\QueryInterface $query */
        $query = call_user_func($this->query);
        if ($this->page === true) {
            if (is_object($query) && $query instanceof \yii\db\QueryInterface) {
                @list($pages, $models) = resolve_pages($query, $this->orderBy);
            } else {
                $pages = null;
                $models = $query;
            }

        } else {
            $pages = null;
            if (is_object($query) && $query instanceof \yii\db\QueryInterface) {
                $models = $query->orderBy($this->orderBy)->all();
            } else {
                $models = $query;
            }
        }

        $this->_pagination = $pages;

        foreach ($models as $item) {
            /* @var \yii\base\Model $item */
            if ($item instanceof \yii\base\Model) {
                $item = $item->toArray();
            }

            foreach ($this->columns as $field => $options) {
                if (!empty($options['callback']) && is_callable($options['callback'])) {
                    $value = call_user_func($options['callback'], $item, $models);
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
     * 解析表格组件的JS脚本
     * @return string
     * @throws \Throwable
     */
    protected function resolveJsScript()
    {
        $tempCommonMap = [];
        $tempCustomMap = [
            'initScript'    => [],
            'clearScript'   => [],
            'getScript'     => [],
        ];

        foreach ($this->_filterColumns as $field => $col) {
            if ($col['control'] != 'custom') {
                $tempCommonMap[$field] = $col['default'];
            } else {
                /* @var CustomControl $widget */
                $widget = $col['widget'];
                $tempCustomMap['initScript'][] = trim($widget->initValuesJsFunction());
                $tempCustomMap['clearScript'][] = trim($widget->clearValuesJsFunction());
                $tempCustomMap['getScript'][] = trim($widget->getValuesJsFunction());
            }
        }

        $scriptTag = $this->_view->renderPhpFile(__DIR__ . '/app.php', [
            'isPage'            => $this->page ? 1 : 0,
            'link'              => Url::current([], ''),
            'filterColumns'     => Json::encode($tempCommonMap),
            'filterCustoms'     => $tempCustomMap,
            'innerScript'       => !empty($this->_js[Table::JS_SCRIPT_INNER]) ? $this->_js[Table::JS_SCRIPT_INNER] : [],
        ]);
        return preg_script($scriptTag);
    }

    /**
     * 注册视图组件实例
     * @return $this
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
        return $this;
    }
}
