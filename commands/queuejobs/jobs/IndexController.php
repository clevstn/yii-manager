<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\commands\queuejobs\jobs;

use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\queuejobs\Queue;
use app\commands\queuejobs\business\Demo;

/**
 * queue jobs demo
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IndexController extends Queue
{
    /**
     * default queue
     * @return int
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionIndex()
    {
        $this->stdout(Demo::getResult(), Console::FG_GREEN);
        return ExitCode::OK;
    }
}