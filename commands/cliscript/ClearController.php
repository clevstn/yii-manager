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
 * 清除服务端缓存命令
 *
 * 1. 清除js和css等静态资源文件脚本命令：
 * yii clear/asset
 *
 * 2. 清除runtime/cache下所有缓存命令：
 * yii clear/cache
 *
 * 3. 清除runtime下所有目录
 * yii clear/runtime
 *
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
        return $this->clearSpecialDir($d);
    }

    /**
     * 清除runtime下缓存
     * @param string $d 指定目录
     * @return int
     * @throws \yii\base\ErrorException
     */
    public function actionCache ($d = '@app/runtime/cache')
    {
        return $this->clearSpecialDir($d);
    }

    /**
     * 删除runtime下所有目录
     * @param string $d 指定目录
     * @return int
     * @throws \yii\base\ErrorException
     */
    public function actionRuntime($d = '@app/runtime')
    {
        return $this->clearSpecialDir($d);
    }


    /**
     * 清除指定目录下的目录
     * @param string $d 指定目录
     * @return int
     * @throws \yii\base\ErrorException
     */
    protected function clearSpecialDir($d)
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
