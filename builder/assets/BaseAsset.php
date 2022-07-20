<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * Builder资源包继承类
 * @author HiLi
 * @since 1.0
 */
abstract class BaseAsset extends AssetBundle
{
    /**
     * @var string asset source path
     */
    public $sourcePath = '@builder/static';
}