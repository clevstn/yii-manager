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
 * Configuration definition interface
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
interface ConfigureInterface
{
    /**
     * Get configuration items
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getConfig();

    /**
     * Get grouping items
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getGroup();
}