<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\commands\cronjobs\business;

/**
 * 计划任务代码演示
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Demo
{
    /**
     * This is demo
     * @return string
     */
    public function getResult()
    {
        return 'This is cron jobs demo. ';
    }
}