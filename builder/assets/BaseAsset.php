<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * Builder资源包继承类
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