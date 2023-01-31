<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\commands\cliscript;

use yii\helpers\Console;
use yii\console\ExitCode;
use yii\helpers\FileHelper;
use yii\console\Controller;

/**
 * Cleanup kit
 * @author cleverstone
 * @since ym1.0
 */
class ClearController extends Controller
{
    /**
     * @var int
     */
    public $success = ExitCode::OK;

    /**
     * Deletes a subdirectory of the special directory
     * default clear the published resource bundles
     *
     * @param string $d 指定目录
     * @return int
     * @throws \yii\base\ErrorException
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
