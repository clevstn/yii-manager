<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\builder\common\CommonController;
use app\builder\ViewBuilder;

/**
 * 管理员操作日志
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AdminBehaviorLogController extends CommonController
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
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionIndex()
    {
        $table = ViewBuilder::table();
        $table->setPage(false);
        $table->setTitle('管理员操作日志');
        $table->setHideCheckbox();
        $table->setQuery(function () {
            
        });
        $table->setColumns([
            'id' => table_column_helper('ID'),
            'name' => table_column_helper('name'),
            'info' => table_column_helper('info'),
            'status' => table_column_helper('info'),
        ]);

        return $table->render($this);
    }
}