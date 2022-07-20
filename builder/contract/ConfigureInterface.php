<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\contract;

/**
 * 配置接口
 * @author HiLi
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