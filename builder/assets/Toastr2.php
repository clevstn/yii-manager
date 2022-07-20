<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Toastr2
 * @author HiLi
 * @since 1.0
 */
class Toastr2 extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'libs/toastr2/toastr.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/toastr2/toastr.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}