<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Layer
 * @author cleverstone <yang_hui_lei@163.com>
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