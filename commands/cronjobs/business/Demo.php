<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\commands\cronjobs\business;

/**
 * 计划任务代码演示
 * @author cleverstone
 * @since ym1.0
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