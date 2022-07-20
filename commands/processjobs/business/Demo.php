<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\commands\processjobs\business;

/**
 * 守护进程业务代码演示
 * @author HiLi
 * @since 1.0
 */
class Demo
{
    /**
     * @return string
     */
    public static function getResult()
    {
        return 'This is process jobs demo. ';
    }
}