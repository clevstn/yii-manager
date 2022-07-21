<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

/**
 * Main Asset
 * @author cleverstone
 * @since 1.0
 */
class MainAsset extends CommonAsset
{
    /**
     * @var array
     */
    public $css = [
        'libs/css/common.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'libs/js/common.js',
        'libs/js/service.js',
    ];
}