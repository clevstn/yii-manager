<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\assets;

use app\builder\assets\MainAsset;
use yii\web\AssetBundle;

/**
 * 接口定义包
 * @author HiLi
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