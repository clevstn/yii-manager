<?php
/**
 * 
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 构建异常捕获器
 * @author cleverstone
 * @since 1.0
 */
class BuilderException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'BuilderException';
    }
}