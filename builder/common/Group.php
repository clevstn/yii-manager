<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/22
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\common;

use yii\base\BaseObject;
use app\models\SystemConfig;
use yii\base\NotSupportedException;
use yii\base\InvalidValueException;
use app\builder\contract\ConfigureInterface;

/**
 * 配置分组逻辑层
 *
 * @property array $config 配置项
 * @property array $group 分组
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
abstract class Group extends BaseObject implements ConfigureInterface
{

    const TYPE_GROUP = 1;       // 类型: 分组
    const TYPE_ITEM = 2;        // 类型: 普通配置

    /**
     * 分组名称
     * @var string
     * @since 1.0
     */
    public $name = '默认分组';

    /**
     * 组别代码
     * @var string
     * @since 1.0
     */
    public $code = 'DEFAULT_GROUP';

    /**
     * 描述
     * @var string
     * @since 1.0
     */
    public $desc = '这是一个默认分组';

    /**
     * 表单提示
     * @var string
     * @since 1.0
     */
    public $formTips = '这是一个默认分组';

    /**
     * 配置项
     * @var array
     * @since 1.0
     */
    private $_config = [];

    /**
     * 获取分组ID
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function getId()
    {
        $context = new static();
        return $context->code;
    }

    /**
     * 初始化配置项
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        $config = $this->define();
        if (!is_array($config) || !is_array(current($config))) {
            throw new InvalidValueException('The method `define()` must return a array. ');
        }

        $this->_config = $config;
    }

    /**
     * {@inheritDoc}
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getConfig()
    {
        $config = $this->_config;
        return $config;
    }

    /**
     * {@inheritDoc}
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getGroup()
    {
        return [
            $this->code,        // 配置项代码
            '',                 // 配置值
            '',                 // 控件类型
            '',                 // 选项
            $this->name,        // 配置名称
            $this->desc,        // 配置描述
            $this->formTips,    // 表单提示
            self::TYPE_GROUP,   // 类型
            '',                 // 所属分组
            now(),              // 时间
        ];
    }

    /**
     * 定义配置
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    abstract public function define();

    /**
     * 校验规则
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function rules()
    {
        return [];
    }

    /**
     * 格式化配置项
     * @param string $code 配置项代码
     * @param mixed $value 配置值
     * @param $control string 控件类型
     * @param $options $options 选项
     * @param string $name 配置名称
     * @param string $desc 配置描述
     * @param string $formTips 表单提示
     * @return array
     * @throws NotSupportedException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function normalizeItem($code, $value, $control, $options, $name, $desc, $formTips)
    {
        if (!in_array($control, SystemConfig::$controlMap, true)) {
            throw new NotSupportedException("{$control} not supported. ");
        }

        return [
            $code,          // 配置项代码
            $value,         // 配置值
            $control,       // 控件类型
            $options,       // 选项
            $name,          // 配置名称
            $desc,          // 配置描述
            $formTips,      // 表单提示
            self::TYPE_ITEM,// 类型
            $this->code,    // 所属分组
            now(),          // 时间
        ];
    }
}