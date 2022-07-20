<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * icheck库
 * @author HiLi
 * @since 1.0
 */
class IcheckAsset extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'libs/icheck/skins/minimal/_all.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/icheck/js/icheck.min.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
    ];
}