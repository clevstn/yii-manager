<?php

namespace app\admin\assets;

use yii\web\AssetBundle;
use app\builder\assets\MainAsset;

/**
 * 首页静态包
 * @author cleverstone
 * @since 1.0
 */
class IndexAsset extends AssetBundle
{
    /**
     * @var array css路径
     * @since 1.0
     */
    public $css = [
        'admin_static/index/index.css',
    ];

    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'admin_static/index/index.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        MainAsset::class,
    ];
}