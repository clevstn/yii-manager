<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\models\AdminUser;
use app\builder\ViewBuilder;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;
use app\models\AdminUserOperationLog as OpLog;

/**
 * 管理员操作日志
 * @author cleverstone
 * @since ym1.0
 */
class AdminBehaviorLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 管理员操作日志列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->title = '管理员操作日志';
        $table->query = function () use ($queryParams) {
            $query = OpLog::query([
                'a.id',
                'a.admin_user_id',    // 管理员ID
                'a.function',         // 功能描述,如:新增管理员
                'a.route',            // 路由
                'a.ip',               // ip
                'a.operate_status',   // 操作状态
                'a.operate_info',     // 操作信息
                'a.client_info',      // 客户端信息
                'a.created_at',       // 操作时间
                'u.username',         // 管理员名称
            ], 'a')->leftJoin(['u' => AdminUser::tableName()], 'a.admin_user_id=u.id');
            if (!empty($queryParams)) {
                $startAt = null;
                $endAt = null;
                if (isset($queryParams['created_at'])) {
                    list($startAt, $endAt) = DateSplitHelper::create($queryParams['created_at'])->reformat()->toArray();
                }

                $query->filterWhere([
                    'and',
                    // 关键词筛选
                    [
                        'or',
                        ['like', 'a.function', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'a.route', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'u.username', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                    ],
                    // 操作状态筛选
                    ['a.operate_status' => isset($queryParams['operate_status']) ? $queryParams['operate_status'] : null],
                    // 操作时间筛选
                    ['between', 'a.created_at', $startAt, $endAt],
                ]);
            }

            return $query;
        };
        $table->orderBy = [
            'a.id' => SORT_DESC,
        ];

        $table->hideCheckbox = true;
        $table->columns = [
            'username' => table_column_helper('管理员', ['style' => ['min-width' => '140px']]),
            'function' => table_column_helper('操作项', ['style' => ['min-width' => '140px']]),
            'route' => table_column_helper('路由', ['style' => ['min-width' => '140px']], function ($item) {
                return to_label($item['route'], true, 'primary');
            }),
            'ip' => table_column_helper('IP', ['style' => ['min-width' => '100px']]),
            'operate_status' => table_column_helper('操作状态', ['style' => ['min-width' => '88px']], function ($item) {
                return OpLog::getStatusLabel($item['operate_status']);
            }),
            'operate_info' => table_column_helper('操作信息', [], function ($item) {
                return html_popover($item['operate_info']);
            }),
            'client_info' => table_column_helper('客户端信息'),
            'created_at' => table_column_helper('操作时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '管理员/功能/路由',
                    'placeholder' => '请输入管理员/功能/路由',
                ]),
                'operate_status' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '操作状态',
                    'placeholder' => '请选择操作状态',
                    'options' => [
                        '1' => '操作成功',
                        '0' => '操作失败',
                    ],
                ]),
                'created_at' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '操作时间',
                    'placeholder' => '请选择操作时间',
                    'range' => 1,
                ]),
            ],
        ];

        return $table->render($this);
    }
}