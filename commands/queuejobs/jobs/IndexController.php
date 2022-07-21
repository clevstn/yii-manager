<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\commands\queuejobs\jobs;

use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\queuejobs\Queue;
use app\commands\queuejobs\business\Demo;

/**
 * queue jobs demo
 * @author cleverstone
 * @since 1.0
 */
class IndexController extends Queue
{
    /**
     * default queue
     * @return int
     */
    public function actionIndex()
    {
        $this->stdout(Demo::getResult(), Console::FG_GREEN);
        return ExitCode::OK;
    }
}