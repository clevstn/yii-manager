<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/22
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

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
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getConfig();

    /**
     * 获取分组
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getGroup();

    /**
     * 校验规则
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function rules();
}