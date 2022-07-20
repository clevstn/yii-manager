<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\contract;

use yii\base\UserException;

/**
 * 构建异常捕获器
 * @author HiLi
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