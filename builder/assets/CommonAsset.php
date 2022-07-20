<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\assets;

use yii\web\YiiAsset;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * 公共依赖包
 * @author HiLi
 * @since 1.0
 */
class CommonAsset extends BaseAsset
{
    /**
     * @var array 注册依赖包
     */
    public $depends = [
        PromiseAsset::class,            // Es6 Promise
        BootstrapPluginAsset::class,    // Bootstrap3
        Toastr2::class,                 // ToaStr2
        Select2Asset::class,            // Select2
        YiiAsset::class,                // Yii2Js
        SpinnerAsset::class,            // Spinner
        FontAwesomeAsset::class,        // FontAwesome
        SweetAlert2::class,             // SweetAlert2
        IcheckAsset::class,             // Icheck
        LaydateAsset::class,            // Laydate
        LayerAsset::class,              // Layer
        WangEditorAsset::class,         // WangEditor
        AngularAsset::class,            // Angular 1.7.5
        AngularSelect2::class,          // Angular select2 archive
        NgUpload::class,                // Angular Files Upload
    ];
}