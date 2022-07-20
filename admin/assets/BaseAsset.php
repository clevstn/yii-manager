<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\assets;

use yii\web\AssetBundle;
use app\builder\assets\MainAsset;

/**
 * admin模块静态资源包基础依赖
 * @author HiLi
 * @since 1.0
 */
abstract class BaseAsset extends AssetBundle
{
    /**
     * @var array 依赖
     */
    public $depends = [
        MainAsset::class,
        ApiAsset::class,
    ];
}