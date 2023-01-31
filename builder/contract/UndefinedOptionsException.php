<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 未定义的选项异常
 * @author cleverstone
 * @since ym1.0
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