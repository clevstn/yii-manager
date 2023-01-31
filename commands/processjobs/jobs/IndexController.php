<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\commands\processjobs\jobs;

use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\processjobs\Process;
use app\commands\processjobs\business\Demo;

/**
 * process jobs demo
 * @author cleverstone
 * @since ym1.0
 */
class IndexController extends Process
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