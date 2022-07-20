<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 无效的实例
 * @author HiLi
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