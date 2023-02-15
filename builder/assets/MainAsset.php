<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * 应用主体包
 * @author cleverstone
 * @since ym1.0
 */
class MainAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $js = [
        'admin_static/lang.js',
        'admin_static/api.js',
    ];

    public $depends = [
        CommonAsset::class, // 公共依赖包
    ];
}