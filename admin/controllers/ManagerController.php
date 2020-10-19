<?php

namespace app\admin\controllers;

use app\models\AdminUser;
use app\builder\ViewBuilder;
use app\builder\form\FieldsOptions;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;

/**
 * 管理员
 * @author cleverstone
 * @since 1.0
 */
class ManagerController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
        'add-user' => ['get', 'post'],
        'toggle' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
        'add-user',
        'toggle',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [];

    /**
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        // 表格构建器对象
        $table = ViewBuilder::table();
        // 表格页标题
        $table->title = '管理员';
        // 查询
        $table->query = function () use ($queryParams) {
            $query = AdminUser::getQuery([
                'id',               // ID
                'username',         // 用户名
                'email',            // 邮箱
                'an',               // 区号
                'mobile',           // 电话号
                'safe_auth',        // 是否开启安全认证, 0:不开启 1:跟随系统 2:邮箱认证 3:短信认证 4:MFA认证
                'open_operate_log', // 是否开启操作日志, 0:关闭 1:跟随系统 2:开启
                'open_login_log',   // 是否开启登录日志, 0:关闭 1:跟随系统 2:开启
                'status',           // 账号状态,0:已封停 1:正常
                'deny_end_time',    // 封停结束时间，null为无限制
                'group',            // 管理组ID, 0为系统管理员
                'identify_code',    // 身份代码
                'pid',              // 父ID
                'created_at',       // 注册时间
                'updated_at',       // 更新时间
            ]);
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
                        ['like', 'username', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'email', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'mobile', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                    ],
                    // 状态筛选
                    ['status' => isset($queryParams['status']) ? $queryParams['status'] : null],
                    // 注册时间筛选
                    ['between', 'created_at', $startAt, $endAt],
                ]);
            }

            return $query;
        };
        $table->orderBy = [
            'id' => SORT_DESC,
        ];
        $table->columns = [
            'username' => table_column_helper('用户名', ['style' => ['min-width' => '120px']]),
            'email' => table_column_helper('邮箱', ['style' => ['min-width' => '120px']]),
            'an_mobile' => table_column_helper('电话号', ['style' => ['min-width' => '120px']], function ($item) {
                return '+' . $item['an'] . ' ' . $item['mobile'];
            }),
            'safe_auth' => table_column_helper('是否开启安全认证', ['style' => ['min-width' => '135px']], function ($item) {
                return AdminUser::getIsSafeAuthLabel($item['safe_auth']);
            }),
            'open_operate_log' => table_column_helper('是否开启操作日志', ['style' => ['min-width' => '135px']], function ($item) {
                return AdminUser::getIsOpenOperateLabel($item['open_operate_log']);
            }),
            'open_login_log' => table_column_helper('是否开启登录日志', ['style' => ['min-width' => '135px']], function ($item) {
                return AdminUser::getIsOpenLoginLogLabel($item['open_login_log']);
            }),
            'status' => table_column_helper('账户状态', [], function ($item) {
                return AdminUser::getStatusLabel($item['status'], true);
            }),
            'deny_end_time' => table_column_helper('封停时间', ['style' => ['min-width' => '150px']], function ($item) {
                return $item['deny_end_time'] ?: '无限制';
            }),
            'group' => table_column_helper('管理组', ['style' => ['min-width' => '120px']], function ($item) {
                return '超级管理员';
            }),
            'identify_code' => table_column_helper('身份码', ['style' => ['min-width' => '130px']]),
            'pid' => table_column_helper('上级', ['style' => ['min-width' => '130px']], function ($item) {
                if (!empty($item['pid'])) {
                    $parentUserInfo = AdminUser::getQuery(['username'])->where(['id' => $item['pid']])->one();
                    if (!empty($parentUserInfo)) {
                        return $parentUserInfo['username'];
                    }
                }

                return '无';
            }),
            'created_at' => table_column_helper('注册时间', ['style' => ['min-width' => '150px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '用户名/邮箱/电话',
                    'placeholder' => '请输入用户名/邮箱/电话',
                ]),
                'status' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '账户状态',
                    'placeholder' => '请选择账户状态',
                    'options' => [
                        '1' => '正常',
                        '0' => '已封停',
                    ],
                ]),
                'created_at' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '注册时间',
                    'placeholder' => '请选择注册时间',
                    'range' => 1,
                ]),
            ],
        ];
        // 导出
        $table->toolbarExport = [
            'heads' => ['ID', '用户名', '邮箱', '电话区号', '电话号码', '账号状态', '封停截止时间', '管理组', '身份代码', '我的上级', '注册时间'],
            'fields' => ['id', 'username', 'email', 'an', 'mobile', 'status', 'deny_end_time', 'group', 'identify_code', 'pid', 'created_at'],
            'columns' => [
                'id',
                'username',
                'email',
                'an',
                'mobile',
                'status' => function ($item) {
                    return AdminUser::getStatusLabel($item['status']);
                },
                'deny_end_time' => function ($item) {
                    return $item['deny_end_time'] ?: '无限制';
                },
                'group' => function ($item) {
                    return '超级管理员';
                },
                'identify_code',
                'pid' => function ($item) {
                    return '无';
                },
                'created_at',
            ],
            'name' => '管理员列表',
        ];
        // 自定义工具栏
        $table->toolbarCustom = [
            // 新增管理员
            table_toolbar_custom_helper('left', [
                'title' => '新增管理员',
                'icon' => 'fa fa-plus',
                'option' => 'modal',
                'route' => 'admin/manager/add-user',
                'width' => '700px',
                'height' => '750px',
            ]),
            // 封停
            table_toolbar_custom_helper('left', [
                'title' => '封停',
                'icon' => 'fa fa-lock',
                'option' => 'modal',
                'route' => 'admin/manager/toggle',
                'width' => '700px',
                'height' => '750px',
                'params' => ['id', 'action' => 'disabled'],
            ]),
            // 解封
            table_toolbar_custom_helper('left', [
                'title' => '解封',
                'icon' => 'fa fa-unlock',
                'option' => 'ajax',
                'route' => 'admin/manager/toggle',
                'params' => ['id', 'action' => 'enabled'],
            ]),
        ];

        return $table->render($this);
    }

    /**
     * 新增管理员
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public function actionAddUser()
    {
        $form = ViewBuilder::form();
        $form->partial = true;
        $form->backBtn = false;
        $form->fields = [
            'parent' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '我的上级',
                'placeholder' => '请填写我的上级用户名/邮箱',
            ]),
            'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '用户名',
                'placeholder' => '请填写用户名',
            ]),
            'password' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                'label' => '密码',
                'placeholder' => '请填写密码',
            ]),
            'repassword' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                'label' => '重复密码',
                'placeholder' => '请确认密码',
            ]),
            'email' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '邮箱',
                'placeholder' => '请填写邮箱',
            ]),
        ];

        return $form->render($this);
    }

    /**
     * 解封、封停
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public function actionToggle()
    {
        return '';
    }
}