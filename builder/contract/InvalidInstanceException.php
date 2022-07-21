<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 无效的实例
 * @author cleverstone
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