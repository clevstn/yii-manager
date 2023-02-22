<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\table;

use Yii;
use yii\helpers\Url;
use app\builder\common\BaseOptions;

/**
 * 表格行操作项选项
 * @author cleverstone
 * @since ym1.0
 */
class RowActionOptions extends BaseOptions
{
    /**
     * @var string 按钮标题和`page`、`modal`标题
     */
    public $title = 'default';

    /**
     * @var string 按钮图标
     */
    public $icon = 'fa fa-paper-plane-o';

    /**
     * @var string 路由
     */
    public $route;

    /**
     * @var array 路由参数，不配置时，默认为主键
     */
    public $params;

    /**
     * @var string 请求动作，当type为ajax时，该配置项有效
     */
    public $method = 'get';

    /**
     * @var string 当前type为modal时有效，指定modal的宽，默认800px
     */
    public $width = '800px';

    /**
     * @var string 当前type为modal时有效，指定modal的高，默认520px
     */
    public $height = '520px';

    /**
     * @var string actionId 操作ID,用于动态展示操作项,需要在`columns`中定义是否展示,返回true则显示, 返回false则隐藏; 注意: 该值必须可以作为js变量
     * 注: 该值必须可以作为js变量
     */
    public $actionId;

    /**
     * 配置初始化
     */
    public function init()
    {
        if (!empty($this->route)) {
            $this->route = Url::to(ltrim($this->route, '/'), '');
        } else {
            $this->route = Url::to(Yii::$app->controller->route, '');
        }

        $this->method = strtolower($this->method);
    }
}