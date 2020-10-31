<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\assets;

use yii\web\AssetBundle;
use app\builder\assets\MainAsset;

/**
 * 首页静态包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IndexAsset extends AssetBundle
{
    /**
     * @var array css路径
     */
    public $css = [
        'admin_static/index/index.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'admin_static/index/index.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        MainAsset::class,
    ];
}