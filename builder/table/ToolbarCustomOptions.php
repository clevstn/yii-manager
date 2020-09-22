<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

use Yii;
use yii\helpers\Url;
use yii\base\BaseObject;

/**
 * 工具栏自定义选项
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ToolbarCustomOptions extends BaseObject
{
    /**
     * 位置
     * - left 左边
     * - right 右边
     * @var string
     * @since 1.0
     */
    public $pos = 'left';

    /**
     * 标题
     * @var string
     * @since 1.0
     */
    public $title = '自定义';

    /**
     * Icon
     * @var string
     * @since 1.0
     */
    public $icon = 'glyphicon glyphicon-send';

    /**
     * 选项
     * - page  页面
     * - modal 模态框
     * - ajax  XMLHttpRequest
     * @var string
     * @since 1.0
     */
    public $option = 'page';

    /**
     * 路由
     * @var string
     * @since 1.0
     */
    public $route = '';

    /**
     * 参数
     * @var string
     * @since 1.0
     */
    public $params = [];

    /**
     * 访问动作, ajax有效
     * @var string
     * @since 1.0
     */
    public $method = 'get';

    /**
     * 当前type为modal时有效，指定modal的宽，默认800px
     * @var string
     * @since 1.0
     */
    public $width = '800px';

    /**
     * 当前type为modal时有效，指定modal的高，默认520px
     * @var string
     * @since 1.0
     */
    public $height = '520px';

    /**
     * 初始化选项
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        if (!empty($this->route)) {
            $this->route = Url::toRoute('/' . ltrim($this->route, '/'));
        } else {
            $this->route = Url::toRoute('/' . Yii::$app->controller->route);
        }

        $this->method = strtolower($this->method);
    }

    /**
     * 输出数组
     * @return array
     * @throws \ReflectionException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function toArray()
    {
        $class = new \ReflectionClass($this);

        $attributes = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $attributes[$property->getName()] = $property->getValue($this);
            }
        }

        return $attributes;
    }
}