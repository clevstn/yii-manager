<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 未定义的选项异常
 * @author HiLi
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