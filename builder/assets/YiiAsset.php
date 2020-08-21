<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * Yii资源包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class YiiAsset extends AssetBundle
{
    /**
     * @var string 源路径
     * @since 1.0
     */
    public $sourcePath = '@yii/assets';

    /**
     * @var array js
     * @since 1.0
     */
    public $js = [
        'yii.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}