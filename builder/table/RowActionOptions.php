<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\table;

use Yii;
use yii\helpers\Url;
use app\builder\common\BaseOptions;

/**
 * 表格行操作项选项
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class RowActionOptions extends BaseOptions
{
    /**
     * 按钮标题和`page`、`modal`标题
     *
     * @var string
     * @since 1.0
     */
    public $title = '默认项';

    /**
     * 按钮图标
     *
     * @var string
     * @since 1.0
     */
    public $icon = 'fa fa-paper-plane-o';

    /**
     * 路由
     *
     * @var string
     * @since 1.0
     */
    public $route;

    /**
     * 路由参数，不配置时，默认为主键
     *
     * @var array
     * @since 1.0
     */
    public $params;

    /**
     * 请求动作，当type为ajax时，该配置项有效
     *
     * @var string
     * @since 1.0
     */
    public $method = 'get';

    /**
     * 当前type为modal时有效，指定modal的宽，默认800px
     *
     * @var string
     * @since 1.0
     */
    public $width = '800px';

    /**
     * 当前type为modal时有效，指定modal的高，默认520px
     *
     * @var string
     * @since 1.0
     */
    public $height = '520px';

    /**
     * 配置初始化
     *
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        if (!empty($this->route)) {
            $this->route = Url::toRoute('/' . ltrim($this->route, '/'));
        } else {
            $this->route = Url::toRoute('/' . Yii::$app->controller->route);
        }

        $this->method = strtolower($this->method);
    }
}