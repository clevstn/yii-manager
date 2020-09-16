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
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\Linkable;
use yii\base\BaseObject;
use yii\base\Controller;
use app\builder\widgets\LinkPager;
use yii\base\NotSupportedException;
use app\builder\contract\BuilderInterface;
use app\builder\contract\NotFoundAttributeException;

/**
 * 表格构建器
 * @property string $title
 * @property boolean $page
 * @property array $columns
 * @property \Closure $query
 * @property array $rowActions
 * @property array|string $orderBy
 * @property boolean $hideCheckbox
 * @property array $checkboxOptions
 * @property array|string $primaryKey
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
     */
    private $_title = '';

    /**
     * 表格列
     * @var array
     * @since 1.0
     * @see $columns
     */
    private $_columns = [];

    /**
     * query
     * @var \Closure
     * @since 1.0
     * @see $query
     */
    private $_query;

    /**
     * @var string|array
     * @since 1.0
     */
    private $_primaryKey = 'id';

    /**
     * @var array
     * @since 1.0
     */
    private $_orderBy = ['id' => SORT_DESC];

    /**
     * 是否分页
     * @since 1.0
     * @see $page
     */
    private $_page = true;

    /**
     * 是否隐藏多选框
     * @var bool
     * @since 1.0
     */
    private $_hideCheckbox = false;

    /**
     * @var array
     * @since 1.0
     */
    private $_checkboxOptions = [
        'style' => 'width:50px;',
        'attribute' => '',
    ];

    /**
     * 设置行操作项
     * @var array
     * @since 1.0
     */
    private $_rowActions = [];

    /**
     * 表格数据
     * @var array
     * @since 1.0
     */
    private $_data = [];

    /**
     * 分页实例
     * @var Linkable|null
     * @since 1.0
     */
    private $_pagination;

    /**
     * 视图组件实例
     * @var View
     * @since 1.0
     */
    private $_view;

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
        if (!empty($title)) {
            $this->_title = $title;
        }

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
        if (!empty($this->_title)) {
            return $this->_title;
        }

        $title = '';
        return $title;
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
        foreach ($columns as $key => $item) {
            // single
            if (is_int($key)) {
                if (is_string($item)) {
                    $this->_columns[$item] = [
                        'title' => $item,
                        'options' => [
                            'style' => '',
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
                        $item['options']['style'] = '';
                    }

                    if (!empty($item['options']['attribute'])) {
                        if (is_array($item['options']['attribute'])) {
                            $item['options']['attribute'] = Html::renderTagAttributes($item['options']['attribute']);
                        }
                    } else {
                        $item['options']['attribute'] = '';
                    }

                } else {
                    $item['options']['style'] = '';
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
        return $this->_rowActions;
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
            return $this->renderHtml($context);
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
        $this->_view->title = $this->getTitle();
        // Register the table widget script js
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        return $context->render($this->_viewPath, [
            'columns'           => $this->columns,
            'hideCheckbox'      => $this->hideCheckbox,
            'checkboxOptions'   => $this->checkboxOptions,
            'rowActions'        => $this->rowActions,
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
            'link' => Yii::$app->request->url,
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