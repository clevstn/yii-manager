<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * Builder资源包继承类
 * @author cleverstone
 * @since 1.0
 */
abstract class BaseAsset extends AssetBundle
{
    /**
     * @var string asset source path
     */
    public $sourcePath = '@builder/static';
}