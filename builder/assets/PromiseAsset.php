<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\assets;

/**
 * Promise Es6
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class PromiseAsset extends BaseAsset
{
    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/promise/es6-promise.min.js',
        'libs/promise/es6-promise.auto.min.js',
    ];
}