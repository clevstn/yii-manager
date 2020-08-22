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
use yii\base\ErrorException;
use yii\base\InvalidValueException;
use app\builder\contract\ConfigureInterface;

/**
 *  A configuration-defined inherited class
 * @property array $config 配置项
 * @property array $group 分组
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
abstract class Group extends BaseObject implements ConfigureInterface
{
    // group type
    const TYPE_GROUP = 1;
    // item type
    const TYPE_ITEM = 2;

    /**
     * @var string Configure group name
     * @since 1.0
     */
    public $name = '默认分组';

    /**
     * @var string Configure group code
     * @since 1.0
     */
    public $code = 'DEFAULT_GROUP';

    /**
     * @var string Configuration group description[read-only]
     * @since 1.0
     */
    public $desc = '这是一个默认分组';

    /**
     * @var string Configuration group form description
     * @since 1.0
     */
    public $formTips = '这是一个默认分组项';

    /**
     * Configuration items
     * @var array
     * @since 1.0
     */
    private $_config = [];

    /**
     * Initializes the configuration item
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        $config = $this->define();
        if (!is_array($config) || !is_array(current($config))) {
            throw new InvalidValueException('The method `define()` must return a array. ');
        }

        foreach ($config as $key => $item) {
            $item = array_values($item);
            if (count($item) !== count($this->group)) {
                throw new ErrorException("The item count error with index of {$key}. ");
            }

            if ($item[5] != self::TYPE_ITEM) {
                throw new ErrorException("The item type error with index of {$key}. ");
            }

            if (strcmp($item[6], $this->code)) {
                throw new ErrorException("The item belong group error with index of {$key}. ");
            }
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
     * Define the configuration
     * the method must be inherited
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    abstract public function define();

    /**
     * {@inheritDoc}
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getGroup()
    {
        return [
            $this->code,
            '',
            $this->name,
            $this->desc,
            $this->formTips,
            self::TYPE_GROUP,
            '',
            now(),
        ];
    }

    /**
     * 格式化配置项
     * @param string $code
     * @param mixed $value
     * @param string $name
     * @param string $desc
     * @param string $formTips
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function normalizeItem($code, $value, $name, $desc, $formTips)
    {
        // ['配置项代码', '配置值', '配置名称', '配置描述', '表单提示', '类型, 1:分组 2:配置', '所属分组', '创建时间']
        return [
            $code,
            $value,
            $name,
            $desc,
            $formTips,
            self::TYPE_ITEM,
            $this->code,
            now(),
        ];
    }
}