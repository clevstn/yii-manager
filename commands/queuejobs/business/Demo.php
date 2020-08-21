<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\commands\queuejobs\business;

/**
 * 队列业务代码演示
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Demo
{
    /**
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function getResult()
    {
        return 'This is queue jobs demo. ';
    }
}