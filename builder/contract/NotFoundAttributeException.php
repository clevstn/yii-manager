<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\contract;

use yii\base\UserException;

class NotFoundAttributeException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'NotFoundAttributeException';
    }
}