<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\assets;

use yii\web\AssetBundle;
use app\builder\assets\MainAsset;

/**
 * admin模块静态资源包基础依赖
 * @author cleverstone
 * @since ym1.0
 */
abstract class BaseAsset extends AssetBundle
{
    /**
     * @var array 依赖
     */
    public $depends = [
        MainAsset::class,
    ];
}