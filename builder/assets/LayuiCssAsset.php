<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\builder\assets;

/**
 * Layui Css包
 * @author cleverstone
 * @since ym1.0
 */
class LayuiCssAsset extends BaseAsset
{
    /**
     * 主UI是bootstrap，所以要置顶Layui中的css
     * @var array
     */
    public $css = [
        'libs/layui-2.7.6/css/layui.css',
    ];
}