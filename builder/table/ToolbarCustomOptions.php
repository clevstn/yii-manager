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
 * 工具栏自定义项选项
 * @author cleverstone
 * @since ym1.0
 */
class ToolbarCustomOptions extends BaseOptions
{
    /**
     * @var string 位置
     * - left 左边
     * - right 右边
     */
    public $pos = 'left';

    /**
     * @var string 标题
     */
    public $title = 'custom';

    /**
     * @var string Icon
     */
    public $icon = 'glyphicon glyphicon-send';

    /**
     * @var string 选项
     * - page  页面
     * - modal 模态框
     * - ajax  XMLHttpRequest
     */
    public $option = 'page';

    /**
     * @var string 路由
     */
    public $route = '';

    /**
     * @var array 参数
     */
    public $params = [];

    /**
     * @var string 访问动作, ajax有效
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
     * 初始化选项
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