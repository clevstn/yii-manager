<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\ExitCode;
use yii\helpers\FileHelper;
use yii\console\Controller;

/**
 * Cleanup kit
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ClearController extends Controller
{
    /**
     * Clear the published resource bundles
     *
     * @return int
     * @throws \yii\base\ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionAsset()
    {
        $subDirs = FileHelper::findDirectories(\Yii::getAlias('@app/web/assets'), ['recursive' => false]);
        if (!empty($subDirs)) {
            foreach ($subDirs as $dir) {
                FileHelper::removeDirectory($dir);
            }
        }

        echo ExitCode::getReason(0);
        return ExitCode::OK;
    }
}
