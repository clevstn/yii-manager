<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 无效的实例
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class InvalidInstanceException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'InvalidInstanceException';
    }
}