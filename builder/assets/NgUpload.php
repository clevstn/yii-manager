<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * angular文件上传插件
 * @author HiLi
 * @since 1.0
 * @see https://github.com/danialfarid/ng-file-upload
 */
class NgUpload extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'libs/ng-upload/ng-upload.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/ng-upload/ng-file-upload-shim.min.js',
        'libs/ng-upload/ng-file-upload.min.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        AngularAsset::class,
    ];
}