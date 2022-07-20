<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Angular-select2
 * @author HiLi
 * @since 1.0
 */
class AngularSelect2 extends BaseAsset
{
    public $js = [
        'libs/angular-1.7.5/angular-select2.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
        Select2Asset::class,
        AngularAsset::class,
    ];
}