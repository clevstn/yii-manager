<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 未定义的选项异常
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class UndefinedOptionsException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'UndefinedOptionsException';
    }
}