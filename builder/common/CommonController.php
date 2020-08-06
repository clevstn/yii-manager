<?php
// +----------------------------------------------------------------------
// | yii-manager控制器继承类
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\common;

use yii\web\Controller;

abstract class CommonController extends Controller
{

    /**
     * yii-manager layouts
     * @var string
     */
    public $layout = '@builder/layouts/layout.php';
}