<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\helper;

use Yii;
use yii\base\ErrorException;
use app\builder\common\Group;
use app\builder\contract\ConfigureInterface;

/**
 * 配置定义助手
 * @author cleverstone
 * @since ym1.0
 */
class ConfigHelper
{
    /**
     * 获取项目配置定义
     * @param string|null $group 配置组代码
     * @return array
     * @throws ErrorException
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
     * 格式化配置定义
     * @return array
     * @throws ErrorException
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
     * 获取分组配置定义
     * @param string|null $group 配置组代码
     * @return array
     * @throws ErrorException
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
     * 格式化分组配置定义
     * @return array
     * @throws ErrorException
     */
    public static function normalizeGroup()
    {
        $groups = self::getGroup();
        return array_values($groups);
    }

    /**
     * 获取配置分组映射类集合
     * @return array
     */
    public static function map()
    {
        return Yii::$app->params['admin_group_config'];
    }
}