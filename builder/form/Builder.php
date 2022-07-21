<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\form;

use Yii;
use yii\web\View;
use yii\base\Controller;
use yii\base\BaseObject;
use app\builder\contract\BuilderInterface;

/**
 * 表单构建器
 * @property string $title      表单标题
 * @property boolean $partial   是否独立布局
 * @property array $fields      表单字段
 * @property bool $backBtn      返回按钮
 * @property bool $autoBack     是否自动返回
 * @property-write array $js    Js脚本
 * @property-write array $css   Css脚本
 * @property-write array $assetBundle
 * @author cleverstone
 * @since 1.0
 */
class Builder extends BaseObject implements BuilderInterface
{
    /**
     * @var string 表单标题
     * @see $title
     * @see setTitle()
     */
    private $_title = '';

    /**
     * @var array 表单字段
     */
    private $_fields = [];

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
     * @var bool 返回按钮
     */
    private $_backBtn = true;

    /**
     * @var bool 是否自动返回
     */
    private $_autoBack = true;

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
     * @var string 局部视图路径
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
    private $_viewPath = '@builder/form/views/index.php';

    /**
     * 初始化
     */
    public function init()
    {
        $this->registerView();
    }

    /**
     * 设置表单名
     * @param string $title
     * @return \app\builder\form\Builder
     */
    public function setTitle($title = '')
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * 获取表单名
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * 设置局部视图
     * @param bool $partial
     * @return \app\builder\form\Builder
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
     * 设置表单字段
     * @param array $options
     * @return $this
     */
    public function setFields(array $options)
    {
        $this->_fields = $options;
        return $this;
    }

    /**
     * 获取表单字段
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * 设置表单返回按钮
     * @param bool $boolean
     * @return $this
     */
    public function setBackBtn($boolean = true)
    {
        $this->_backBtn = $boolean;
        return $this;
    }

    /**
     * 获取是否设置返回按钮
     * @return bool
     */
    public function getBackBtn()
    {
        return $this->_backBtn;
    }

    /**
     * 设置自动返回
     * @param bool $boolean
     * @return $this
     */
    public function setAutoBack($boolean = true)
    {
        $this->_autoBack = $boolean;
        return $this;
    }

    /**
     * 获取是否自动返回
     * @return bool
     */
    public function getAutoBack()
    {
        return $this->_autoBack;
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
    public function setJs($js, $pos = Form::JS_SCRIPT_INNER)
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
     * 渲染入口
     * @param Controller $context
     * @return string
     * @throws \Throwable
     */
    public function render(Controller $context)
    {
        $oldLayout = $context->layout;
        if ($this->partial === true) {
            // 独立布局
            $context->layout = $this->layoutPartial;
        } else {
            // 默认布局
            $context->layout = $this->layoutPath;
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
     */
    protected function renderHtml(Controller $context)
    {
        // Set table title
        $this->_view->title = $this->title;

        // 注册Js,位于表格脚本上方
        if (!empty($this->_js[Form::JS_SCRIPT_TOP])) {
            $scriptTopJs = $this->_js[Form::JS_SCRIPT_TOP];
            foreach ($scriptTopJs as $topJs) {
                $this->_view->registerJs($topJs, View::POS_END);
            }
        }

        // Register the table widget script js
        $this->_view->registerJs($this->resolveJsScript(), View::POS_END);

        // 注册Js,位于表格脚本下方
        if (!empty($this->_js[Form::JS_SCRIPT_BOTTOM])) {
            $scriptBottomJs = $this->_js[Form::JS_SCRIPT_BOTTOM];
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
            '_fields'  => $this->fields,             // 表单字段
            '_backBtn' => $this->backBtn,            // 是否设置返回按钮
        ]);
    }

    /**
     * 解析表单组件JS脚本
     * @return string
     * @throws \Throwable
     */
    protected function resolveJsScript()
    {
        $scriptTag = $this->_view->renderPhpFile(__DIR__ . '/app.php', [
            '_fields'           => $this->fields,              // 表单字段
            '_autoBack'         => $this->autoBack,            // 提交完成后是否自动返回
            // 插入表单脚本内部的Js脚本
            '_innerScript'      => !empty($this->_js[Form::JS_SCRIPT_INNER]) ? $this->_js[Form::JS_SCRIPT_INNER] : [],
        ]);
        return preg_script($scriptTag);
    }

    /**
     * 注册视图组件实例到当前构建器
     * @return \app\builder\form\Builder
     */
    protected function registerView()
    {
        $this->_view = Yii::$app->getView();
        return $this;
    }
}