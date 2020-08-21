<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\commands\cronjobs\jobs;

use app\commands\cronjobs\business\Demo;
use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\cronjobs\Cron;

/**
 * cron jobs demo
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IndexController extends Cron
{
    /**
     * default cron
     * @return int
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionIndex()
    {
        $business = new Demo();
        $this->stdout($business->getResult(), Console::FG_GREEN);

        return ExitCode::OK;
    }
}