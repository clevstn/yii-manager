<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Layer
 * @author HiLi
 * @since 1.0
 */
class LayerAsset extends BaseAsset
{
    /**
     * @var array
     */
    public $js = [
        'libs/layer/layer.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
    ];
}