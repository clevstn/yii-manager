<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

/**
 * Promise Es6
 * @author HiLi
 * @since 1.0
 */
class PromiseAsset extends BaseAsset
{
    /**
     * @var array js路径
     */
    public $js = [
        'libs/promise/es6-promise.min.js',
        'libs/promise/es6-promise.auto.min.js',
    ];
}