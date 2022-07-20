<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Angular
 * @author HiLi
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