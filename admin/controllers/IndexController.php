<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\builder\ViewBuilder;
use app\builder\common\CommonController;

/**
 * 首页
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IndexController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'index',
    ];

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return ViewBuilder::table()
            ->setData([
                ['name' => 'Tom', 'sex' => '男'],
                ['name' => 'Sunny', 'sex' => '女'],
            ])
            ->setColumns([
                'name' => table_column_helper('名称'),
                'sex' => table_column_helper('性别'),
            ])
            ->render($this);
    }
}
