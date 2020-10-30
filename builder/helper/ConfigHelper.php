<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\helper;

use Yii;
use yii\base\ErrorException;
use app\builder\common\Group;
use app\builder\contract\ConfigureInterface;

/**
 * 配置助手
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ConfigHelper
{
    /**
     * 获取配置
     *
     * @param string|null $group 配置组代码
     * @return array
     * @throws ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function getConfig($group = null)
    {
        $configMap = [];
        /* @var $context Group */
        foreach (self::map() as $class) {
            $context = new $class;
            if (!$context instanceof ConfigureInterface) {
                throw new ErrorException($class . ' is expected to be an implementation of ' . ConfigureInterface::class);
            }

            $configMap[$context->code] = $context->config;
        }

        if (empty($group)) {
            return $configMap;
        }

        return !empty($configMap[$group]) ? $configMap[$group] : [];
    }

    /**
     * 格式化配置
     *
     * @return array
     * @throws ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function normalizeConfig()
    {
        $config = self::getConfig();
        $map = [];
        foreach ($config as $item) {
            $map = array_merge($map, $item);
        }

        return $map;
    }

    /**
     * 获取分组
     *
     * @param string|null $group 配置组代码
     * @return array
     * @throws ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function getGroup($group = null)
    {
        $groupMap = [];
        /* @var $context Group */
        foreach (self::map() as $class) {
            $context = new $class;
            if (!$context instanceof ConfigureInterface) {
                throw new ErrorException($class . ' is expected to be an implementation of ' . ConfigureInterface::class);
            }

            $groupMap[$context->code] = $context->group;
        }

        if (empty($group)) {
            return $groupMap;
        }

        return !empty($groupMap[$group]) ? $groupMap[$group] : [];
    }

    /**
     * 格式化分组
     *
     * @return array
     * @throws ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function normalizeGroup()
    {
        $groups = self::getGroup();
        return array_values($groups);
    }

    /**
     * 分组集合
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function map()
    {
        return Yii::$app->params['group_config'];
    }
}