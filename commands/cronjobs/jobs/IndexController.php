<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\commands\cronjobs\jobs;

use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\cronjobs\Cron;
use app\commands\cronjobs\business\Demo;

/**
 * cron jobs demo
 * @author cleverstone
 * @since 1.0
 */
class IndexController extends Cron
{
    /**
     * default cron
     * @return int
     */
    public function actionIndex()
    {
        $business = new Demo();
        $this->stdout($business->getResult(), Console::FG_GREEN);

        return ExitCode::OK;
    }
}