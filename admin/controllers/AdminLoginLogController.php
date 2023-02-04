<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\models\AdminUser;
use yii\helpers\VarDumper;
use app\builder\ViewBuilder;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;
use app\models\AdminUserLoginLog as AdLoginLog;

/**
 * 管理员登录日志
 * @author cleverstone
 * @since ym1.0
 */
class AdminLoginLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 管理员登录日志列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->title = '管理员登录日志';
        $table->query = function () use ($queryParams) {
            $query = AdLoginLog::query([
                'a.id',
                'a.admin_user_id',    // 管理员ID
                'a.identify_type',    // 认证类型, 0:基本认证 1:邮箱认证 2:短信认证 3:MFA认证
                'a.client_info',      // 客户端信息
                'a.attempt_info',     // 尝试信息
                'a.attempt_status',   // 尝试状态, 0:失败 1:成功
                'a.error_type',       // 自定义错误类型
                'a.login_ip',         // 登录IP
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
                        ['like', 'a.error_type', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],  // 错误类型
                        ['like', 'a.login_ip', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],    // 登录IP
                        ['like', 'u.username', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],    // 用户名
                    ],
                    // 尝试状态筛选
                    ['a.attempt_status' => isset($queryParams['attempt_status']) ? $queryParams['attempt_status'] : null],
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
            'username' => table_column_helper('管理员', ['style' => ['min-width' => '100px']]),
            'identify_type' => table_column_helper('认证类型', ['style' => ['min-width' => '88px']], function ($item) {
                return AdLoginLog::getIdentifyLabel($item['identify_type']);
            }),
            'client_info' => table_column_helper('客户端信息', ['style' => ['min-width' => '150px']]),
            'attempt_info' => table_column_helper('尝试信息', ['style' => ['min-width' => '140px']], function ($item) {
                return str_replace(['\\\'', '\'[', ']\''], ['\'', '[', ']'], VarDumper::dumpAsString($item['attempt_info'], 10, true));
            }),
            'attempt_status' => table_column_helper('操作状态', ['style' => ['min-width' => '88px']], function ($item) {
                return AdLoginLog::getAttemptStatusLabel($item['attempt_status']);
            }),
            'error_type' => table_column_helper('错误类型', ['style' => ['min-width' => '130px']]),
            'login_ip' => table_column_helper('login_ip', ['style' => ['min-width' => '100px']]),
            'created_at' => table_column_helper('操作时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '错误类型/登录IP/用户名',
                    'placeholder' => '请输入错误类型/登录IP/用户名',
                ]),
                'attempt_status' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '尝试状态',
                    'placeholder' => '请选择尝试状态',
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