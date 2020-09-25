<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\form;

use Yii;
use yii\web\View;
use yii\base\Controller;
use yii\base\BaseObject;
use app\builder\contract\BuilderInterface;

/**
 * 表单构建器
 * @property string $title
 * @property boolean $partial
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Builder extends BaseObject implements BuilderInterface
{
    /**
     * 表单标题
     * @var string
     * @since 1.0
     * @see $title
     * @see setTitle()
     */
    private $_title = '';

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
    private $_viewPath = '@builder/form/views/index.php';

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
     * 设置表单名
     * @param string $title
     * @return \app\builder\form\Builder
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function setTitle($title = '')
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * 获取表单名
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * 设置局部视图
     * @param bool $partial
     * @return \app\builder\form\Builder
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
     * 渲染入口
     * @param Controller $context
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function render(Controller $context)
    {
        $oldLayout = $context->layout;
        if ($this->partial === true) {
            $context->layout = $this->layoutPartial;
        }

        $viewBody = $this->renderHtml($context);
        $context->layout = $oldLayout;
        
        return $viewBody;
    }

    /**
     * 渲染Html
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

        return $context->render($this->_viewPath, []);
    }

    /**
     * 解析表单组件JS脚本
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function resolveJsScript()
    {
        $scriptTag = $this->_view->renderPhpFile(__DIR__ . '/app.php', []);
        return preg_script($scriptTag);
    }

    /**
     * 注册视图组件实例
     * @return \app\builder\form\Builder
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
        return $this;
    }
}