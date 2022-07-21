<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\common;

use yii\base\BaseObject;
use app\models\SystemConfig;
use yii\base\NotSupportedException;
use yii\base\InvalidValueException;
use app\builder\contract\ConfigureInterface;

/**
 * 配置分组逻辑层
 * @property array $config 配置项
 * @property array $group 分组
 * @author cleverstone
 * @since 1.0
 */
abstract class Group extends BaseObject implements ConfigureInterface
{
    // 类型: 分组
    const TYPE_GROUP = 1;
    // 类型: 普通配置
    const TYPE_ITEM = 2;

    /**
     * @var string 分组名称
     */
    public $name = 'default group';

    /**
     * @var string 组别代码
     */
    public $code = 'DEFAULT_GROUP';

    /**
     * @var string 描述
     */
    public $desc = 'this is default group';

    /**
     * @var string 表单提示
     */
    public $formTips = 'this is default group';

    /**
     * @var array 配置项
     */
    private $_config = [];

    /**
     * 获取分组ID
     * @return string
     */
    public static function getId()
    {
        $context = new static();
        return $context->code;
    }

    /**
     * 初始化配置项
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
     */
    public function getConfig()
    {
        $config = $this->_config;
        return $config;
    }

    /**
     * {@inheritDoc}
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
     * @throws \yii\base\NotSupportedException
     * @return array
     */
    abstract public function define();

    /**
     * 校验规则
     * @return array
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