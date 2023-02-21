<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\notify;

use app\builder\traits\Http;
use yii\web\Controller;

/**
 * 继承类
 * @author cleverstone
 * @since ym1.0
 */
class BaseController extends Controller
{
    use Http;

    /**
     * 关闭Csrf
     * @var bool
     */
    public $enableCsrfValidation = false;
}