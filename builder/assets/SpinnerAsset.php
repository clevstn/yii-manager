<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Spinner modal
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
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