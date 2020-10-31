<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\contract;

/**
 * 配置接口
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
interface ConfigureInterface
{
    /**
     * 获取配置
     * @return array
     */
    public function getConfig();

    /**
     * 获取分组
     * @return array
     */
    public function getGroup();

    /**
     * 校验规则
     * @return array
     */
    public function rules();
}