<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Angular
 * @author cleverstone
 * @since 1.0
 */
class AngularAsset extends BaseAsset
{
    /**
     * @var array js路径
     */
    public $js = [
        'libs/angular-1.7.5/angular.min.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
    ];
}