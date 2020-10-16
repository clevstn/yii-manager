<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\builder\table\ToolbarFilterOptions;
use app\models\AdminUser;
use app\builder\ViewBuilder;
use app\builder\common\CommonController;

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
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->setTitle('管理员操作日志');
        $table->setHideCheckbox();
        $table->setQuery(function () use ($queryParams) {
            $query = AdminUser::find();
            $query->filterWhere([
                '=', 'username', isset($queryParams['keyword']) ? $queryParams['keyword'] : null
            ]);

            return $query;
        });
        $table->setOrderBy(['id' => SORT_DESC]);
        $table->setColumns([
            'id' => table_column_helper('ID'),
            'name' => table_column_helper('标题', [], function ($item) {
                return $item['username'];
            }),
            'info' => table_column_helper('信息', [], function ($item) {
                return $item['password'];
            }),
            'status' => table_column_helper('状态', [], function ($item) {
                return '<span class="label label-success">正常</span>';
            }),
        ]);
        $table->setToolbarRefresh();
        $table->setToolbarFilter([
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_TEXT,
                    'label'         => '关键词',
                    'placeholder'   => '请输入关键词',
                ]),
                'status' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_SELECT,
                    'label'         => '登陆状态',
                    'placeholder'   => '请选择状态',
                    'options'       => [
                       '1' => '正常',
                       '2' => '异常',
                    ],
                ]),
            ],
        ]);

        return $table->render($this);
    }
}