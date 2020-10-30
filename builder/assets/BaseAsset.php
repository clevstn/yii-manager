<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * Builder资源包继承类
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
abstract class BaseAsset extends AssetBundle
{
    /**
     * asset source path
     * @var string
     */
    public $sourcePath = '@builder/static';
}