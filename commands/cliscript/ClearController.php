<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands\cliscript;

use yii\helpers\Console;
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
     * @var int
     * @since 1.0
     */
    public $success = ExitCode::OK;

    /**
     * Deletes a subdirectory of the special directory
     * default clear the published resource bundles
     * @param string $d 指定目录
     * @return int
     * @throws \yii\base\ErrorException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionAsset($d = '@app/web/assets')
    {
        $subDirs = FileHelper::findDirectories(\Yii::getAlias($d), ['recursive' => false]);
        if (!empty($subDirs)) {
            foreach ($subDirs as $dir) {
                FileHelper::removeDirectory($dir);
            }
        }

        $this->stdout(ExitCode::getReason($this->success), Console::FG_GREEN);
        return $this->success;
    }
}
