<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use yii\log\DbTarget;
use app\models\SystemLog;

/**
 * 系统日志db存储引擎
 * @author cleverstone
 * @since ym1.0
 */
class LogDbTarget extends DbTarget
{
    /**
     * @var string name of the DB table to store cache content. Defaults to "log".
     */
    public $logTable = SystemLog::TABLE_NAME;
}