<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\assets;

use app\builder\assets\MainAsset;
use yii\web\AssetBundle;

/**
 * 接口定义包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ApiAsset extends AssetBundle
{
    /**
     * @var array js路径
     */
    public $js = [
        'admin_static/lang.js',
        'admin_static/api.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        MainAsset::class,
    ];
}