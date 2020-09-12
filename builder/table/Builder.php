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
use yii\base\Controller;
use yii\base\BaseObject;
use app\builder\contract\BuilderInterface;

/**
 * 表格构建器
 * @property string $title  表格名
 * @property array $data    表格数据
 * @property array $columns 数据列
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Builder extends BaseObject implements BuilderInterface
{
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
     * 初始化
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

        // 从节点获取表格名
        $title = '默认表格名';
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
        return $this;
    }

    public function getData()
    {

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
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setColumns(array $columns)
    {

        return $this;
    }

    public function getColumns()
    {
        return [];
    }

    /**
     * 渲染表格
     * @param Controller $context
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render(Controller $context)
    {
        $this->_view->title = $this->getTitle();
        $columns = $this->getColumns();
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        return $context->render($this->_viewPath, $columns);
    }

    /**
     * 解析table组件js脚本
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function resolveJsScript()
    {
        return $this->_view->renderPhpFile(__DIR__ . '/app.js');
    }

    /**
     * 注册view组件实例
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
    }
}