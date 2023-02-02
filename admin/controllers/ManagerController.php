<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\admin\controllers;

use app\models\AreaCode;
use app\models\AdminUser;
use app\builder\ViewBuilder;
use app\builder\form\FieldsOptions;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;

/**
 * 管理员
 * @author cleverstone
 * @since ym1.0
 */
class ManagerController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index'     => ['get'],
        'add-user'  => ['get', 'post'],
        'toggle'    => ['get', 'post'],
        'edit'      => ['get', 'post'],
        'group'     => ['get', 'post'],
    ];

    /**
     * 管理员列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
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
            $query = AdminUser::query([
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
            'username' => table_column_helper('用户名', ['style' => ['min-width' => '150px']]),
            'email' => table_column_helper('邮箱', ['style' => ['min-width' => '150px']]),
            'an_mobile' => table_column_helper('电话号', ['style' => ['min-width' => '130px']], function ($item) {
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
            'status_label' => table_column_helper('账户状态', [], function ($item) {
                return AdminUser::getStatusLabel($item['status'], true);
            }),
            'deny_end_time' => table_column_helper('封停时间', ['style' => ['min-width' => '150px']], function ($item) {
                if ($item['status'] == AdminUser::STATUS_NORMAL) {
                    return '空';
                } else {
                    return $item['deny_end_time'] ?: '无限制';
                }
            }),
            'group' => table_column_helper('管理组', ['style' => ['min-width' => '120px']], function ($item) {
                return '超级管理员';
            }),
            'identify_code' => table_column_helper('身份码', ['style' => ['min-width' => '130px']]),
            'pid' => table_column_helper('上级', ['style' => ['min-width' => '150px']], function ($item) {
                if (!empty($item['pid'])) {
                    $parentUserInfo = AdminUser::query(['username'])->where(['id' => $item['pid']])->one();
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
                'status_label' => function ($item) {
                    return AdminUser::getStatusLabel($item['status']);
                },
                'deny_end_time' => function ($item) {
                    if ($item['status'] == AdminUser::STATUS_NORMAL) {
                        return '空';
                    } else {
                        return $item['deny_end_time'] ?: '无限制';
                    }
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
                'width' => '800px',
                'height' => '750px',
            ]),
            // 封停
            table_toolbar_custom_helper('left', [
                'title' => '封停',
                'icon' => 'fa fa-lock',
                'option' => 'modal',
                'route' => 'admin/manager/toggle',
                'width' => '700px',
                'height' => '500px',
                'params' => ['id', 'action' => 'disabled'],
            ]),
            // 解封
            table_toolbar_custom_helper('left', [
                'title' => '解封',
                'icon' => 'fa fa-unlock',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/manager/toggle',
                'params' => ['id', 'action' => 'enabled'],
            ]),
        ];
        // 行操作
        $table->rowActions = [
            table_action_helper('modal', [
                'title' => '基本编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/manager/edit',
                'width' => '800px',
                'height' => '700px',
            ]),
            table_action_helper('modal', [
                'title' => '更改管理组',
                'icon' => 'fa fa-users',
                'route' => 'admin/manager/group',
                'width' => '700px',
                'height' => '500px',
            ]),
        ];

        return $table->render($this);
    }

    /**
     * 新增管理员
     * @return mixed|string
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public function actionAddUser()
    {
        if ($this->isPost) {
            $model = new AdminUser();
            $model->setScenario('add');
            if ($model->load($this->post) && $model->validate()) {
                $pid = 0;
                $parentPath = '';
                if (!empty($model->parent)) {
                    $parentInstance = AdminUser::findByUsername($model->parent);
                    if (empty($parentInstance)) {
                        return $this->asFail('我的上级不存在');
                    }

                    $pid = $parentInstance['id'];
                    $parentPath = $parentInstance['path'];
                }

                $model->identify_code = $model->generateIdentifyCode();
                $model->pid = $pid;
                $model->path = AdminUser::makePath($pid, $parentPath);

                return $model->save(false) ? $this->asSuccess('新增成功') : $this->asFail('新增失败');
            } else {
                return $this->asFail($model->error);
            }
        } else {
            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'parent' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '我的上级',
                    'placeholder' => '请填写我的上级用户名',
                    'required' => false,
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
                'an' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '电话区号',
                    'placeholder' => '请选择电话区号',
                    'default' => '86',
                    'options' => AreaCode::areaCodes(),
                ]),
                'mobile' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '手机号',
                    'placeholder' => '请填写手机号',
                ]),
                'safe_auth' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启安全认证',
                    'default' => AdminUser::SAFE_AUTH_FOLLOW_SYSTEM,
                    'options' => AdminUser::safeMap(),
                    'comment' => '开启OTP认证后，请前往【我的】绑定Google Authenticator。',
                ]),
                'open_operate_log' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启操作日志',
                    'default' => AdminUser::OPERATE_LOG_FOLLOW,
                    'options' => AdminUser::operationMap(),
                ]),
                'open_login_log' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启登录日志',
                    'default' => AdminUser::LOGIN_LOG_FOLLOW,
                    'options' => AdminUser::loginMap(),
                ]),
                'group' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '管理组',
                    'placeholder' => '请选择管理组',
                    'options' => [
                        '0' => '超级管理员',
                        '1' => '普通管理员',
                        '2' => '市场调查员',
                    ],
                ]),
            ];

            return $form->render($this);
        }
    }

    /**
     * 编辑管理员
     * @return mixed|string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionEdit()
    {
        if ($this->isGet) {
            $queryParams = $this->get;
            if (empty($queryParams) || empty($queryParams['id'])) {
                return $this->asFail('参数错误');
            }

            $identify = AdminUser::findIdentity($queryParams['id']);
            if (empty($identify)) {
                return $this->asFail('管理员不存在');
            }

            // 渲染表单
            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $identify->id,
                ]),
                'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '用户名',
                    'placeholder' => '请填写用户名',
                    'default' => $identify->username,
                    'required' => false,
                    'attribute' => ['disabled' => true],
                ]),
                'password' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                    'label' => '密码',
                    'placeholder' => '请填写密码',
                    'required' => false,
                    'comment' => '不填写，则为不修改',
                ]),
                'repassword' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                    'label' => '重复密码',
                    'placeholder' => '请确认密码',
                    'required' => false,
                    'comment' => '不填写，则为不修改',
                ]),
                'email' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '邮箱',
                    'placeholder' => '请填写邮箱',
                    'default' => $identify->email,
                ]),
                'an' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '电话区号',
                    'placeholder' => '请选择电话区号',
                    'default' => $identify->an,
                    'options' => AreaCode::areaCodes(),
                ]),
                'mobile' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '手机号',
                    'placeholder' => '请填写手机号',
                    'default' => $identify->mobile,
                ]),
                'safe_auth' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启安全认证',
                    'default' => $identify->safe_auth,
                    'options' => AdminUser::safeMap(),
                    'comment' => '开启OTP认证后，请前往【我的】绑定Google Authenticator。',
                ]),
                'open_operate_log' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启操作日志',
                    'default' => $identify->open_operate_log,
                    'options' => AdminUser::operationMap(),
                ]),
                'open_login_log' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                    'label' => '是否开启登录日志',
                    'default' => $identify->open_login_log,
                    'options' => AdminUser::loginMap(),
                ]),
            ];

            return $form->render($this);
        } else {
            // 提交编辑
            $bodyParams = $this->post;
            // 参数校验
            if (empty($bodyParams['id'])) {
                return $this->asFail('参数错误');
            }

            // 查询校验
            $model = AdminUser::query(['id', 'username', 'password', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log'])->where(['id' => $bodyParams['id']])->one();
            if (empty($model)) {
                return $this->asFail('管理员不存在');
            }

            // 设置验证场景
            $model->setScenario('edit');
            // 不更新[[id]]
            unset($bodyParams['id']);
            // 数据校验
            if ($model->load($bodyParams) && $model->validate()) {
                $result = $model->save(false);
                return $result ? $this->asSuccess('编辑成功') : $this->asFail('编辑失败');
            } else {
                return $this->asFail($model->error);
            }
        }
    }

    /**
     * 解封、封停
     * @return mixed|string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionToggle()
    {
        if ($this->isGet) {
            // `disabled`获取表单
            $queryParams = $this->get;
            if (
                empty($queryParams)
                || empty($queryParams['id'])
                || empty($queryParams['action'])
            ) {
                return $this->asFail('参数错误');
            }

            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $queryParams['id'],
                ]),
                'action' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $queryParams['action'],
                ]),
                'deny_end_time' => form_fields_helper(FieldsOptions::CONTROL_DATETIME, [
                    'label' => '封停截止时间',
                    'placeholder' => '请选择封停截止时间',
                    'comment' => '保留为空，代表封停无限制。',
                ]),
            ];

            return $form->render($this);
        } else {
            // `enabled`和`disabled`提交表单
            $bodyParam = $this->post;
            $model = new AdminUser();
            $model->setScenario('status-action');
            if ($model->load($bodyParam) && $model->validate()) {
                $result = AdminUser::updateAll([
                    'status' => $model->action == 'disabled' ? AdminUser::STATUS_DENY : AdminUser::STATUS_NORMAL,
                    'deny_end_time' => $model->action == 'disabled' ? $model->deny_end_time : null,
                ], ['in', 'id', explode(',', $model->id)]);
                return $result ? $this->asSuccess('操作成功') : $this->asSuccess('操作失败');
            } else {
                return $this->asFail($model->error);
            }
        }
    }

    /**
     * 更改管理组
     * @return string
     */
    public function actionGroup()
    {
        return '';
    }
}