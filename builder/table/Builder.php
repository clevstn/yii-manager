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
use yii\web\Linkable;
use yii\base\BaseObject;
use yii\base\Controller;
use app\builder\widgets\LinkPager;
use yii\base\NotSupportedException;
use app\builder\contract\BuilderInterface;

/**
 * 表格构建器
 * @property string $title   标题
 * @property array $data     数据
 * @property Linkable $pages 分页
 * @property array $columns  列
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Builder extends BaseObject implements BuilderInterface
{
    /**
     * @var string
     * @since 1.0
     */
    private $_viewPath = '@builder/table/views/index.php';

    /**
     * @var View
     * @since 1.0
     */
    private $_view;

    /**
     * @var string
     * @since 1.0
     */
    private $_title = '';

    /**
     * @var array
     * @since 1.0
     */
    private $_data = [];

    /**
     * @var array
     * @since 1.0
     */
    private $_columns = [];

    /**
     * @var Linkable
     * @since 1.0
     */
    private $_pages;

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
     * 设置表格数据
     * @param array $data
     * ```php
     *  ViewBuilder::table()
     *      ->setData([
     *          ['name' => 'Tom', 'sex' => '男'],
     *          ['name' => 'Sunny', 'sex' => '女'],
     *      ])
     *      ->setColumns([
     *          'name' => table_column_helper('名称'),
     *          'sex' => table_column_helper('性别'),
     *      ])
     *      ->render($this)
     * ```
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * 获取表格数据
     * @param array $columns
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getData(array $columns = [])
    {
        if (empty($columns)) {
            $columns = $this->_columns;
        }

        $holeColumns = [];
        foreach ($this->_data as $item) {
            $line = [];
            foreach ($columns as $field => $options) {
                if (!empty($options['callback']) && is_callable($options['callback'])) {
                    $value = call_user_func($options['callback'], $item);
                } elseif (isset($item[$field])) {
                    $value = html_escape($item[$field]);
                } else {
                    $value = '';
                }

                $line[$field] = $value;
            }

            array_push($holeColumns, $line);
        }

        return $holeColumns;
    }

    /**
     * 设置数据列
     * @param array $columns
     * ```php
     * ViewBuilder::table()
     *      ->setColumns([
     *          'name' => table_column_helper('名称'),
     *          'sex' => table_column_helper('性别'),
     *      ])
     *      ->render($this)
     *
     * ```
     * @return $this
     * @throws NotSupportedException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $key => $item) {
            if (is_int($key)) {
                if (is_string($item)) {
                    $this->_columns[$item] = [
                        'title' => $item,
                        'options' => [],
                        'callback' => null,
                    ];
                }

                throw new NotSupportedException('The columns item is not supported. ');
            } else {
                if (!empty($item['options'])) {
                    if (!empty($item['options']['style']) && is_array($item['options']['style'])) {
                        $item['options']['style'] = implode(' ', $item['options']['style']);
                    }

                    if (!empty($item['options']['attribute']) && is_array($item['options']['attribute'])) {
                        $item['options']['attribute'] = implode(' ', $item['options']['attribute']);
                    }
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
     * 设置分页
     * @param Linkable $pages
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setPages(Linkable $pages)
    {
        $this->_pages = $pages;
        return $this;
    }

    /**
     * 获取分页
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getPages()
    {
        return $this->_pages;
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
        return Json::encode([
            'data' => $this->data,
            'page' => LinkPager::widget([
                'pagination' => $this->pages,
            ]),
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
        // Sets table title
        $this->_view->title = $this->getTitle();

        // Register the table widget script js
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        return $context->render($this->_viewPath, [
            'columns' => $this->columns,
        ]);
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
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
        return $this;
    }
}