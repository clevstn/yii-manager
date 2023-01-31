<?php
/**
 * 
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Layer
 * @author cleverstone
 * @since ym1.0
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