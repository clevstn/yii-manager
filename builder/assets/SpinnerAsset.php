<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Spinner modal
 * @author cleverstone
 * @since ym1.0
 */
class SpinnerAsset extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'libs/spinner/spinner.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/spinner/spinner.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}