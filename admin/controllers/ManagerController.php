<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\admin\controllers;

use app\components\Uploads;
use app\models\AdminUserQuickAction;
use app\models\AuthMenu;
use app\models\AuthRelations;
use Yii;
use app\extend\Extend;
use yii\helpers\ArrayHelper;
use app\models\AuthGroups;
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
        'mfa-bind'     => ['get'],
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
            $query = AdminUser::activeQuery([
                'id',               // ID
                'username',         // 用户名
                'email',            // 邮箱
                'an',               // 区号
                'mobile',           // 电话号
                'status',           // 账号状态,0:已封停 1:正常
                'deny_end_time',    // 封停结束时间，null为无限制
                'group',            // 管理组ID, 0为系统管理员
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
            'status_label' => table_column_helper('账户状态', [], function ($item) {
                return AdminUser::getStatusLabel($item['status'], true);
            }),
            'deny_end_time' => table_column_helper('封停时间', ['style' => ['min-width' => '150px']], function ($item) {
                if ($item['status'] == AdminUser::STATUS_NORMAL) {
                    return '--';
                } else {
                    return $item['deny_end_time'] ?: '无限制';
                }
            }),
            'group' => table_column_helper('管理组', ['style' => ['min-width' => '120px']], function ($item) {
                return AuthGroups::getGroupName($item['group']);
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
            'heads' => ['ID', '用户名', '邮箱', '电话区号', '电话号码', '账号状态', '封停截止时间', '管理组', '注册时间'],
            'fields' => ['id', 'username', 'email', 'an', 'mobile', 'status', 'deny_end_time', 'group', 'created_at'],
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
                    return AuthGroups::getGroupName($item['group']);
                },
                'created_at',
            ],
            'name' => '管理员列表',
        ];
        // 自定义工具栏
        $table->toolbarCustom = [
            // 新增管理员
            table_toolbar_custom_helper('left', [
                'title' => '新增',
                'icon' => 'fa fa-plus',
                'option' => 'modal',
                'route' => 'admin/manager/add-user',
                'width' => '700px',
                'height' => '550px',
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
                'width' => '70%',
                'height' => '97%',
            ]),
            table_action_helper('modal', [
                'title' => '更改管理组',
                'icon' => 'fa fa-users',
                'route' => 'admin/manager/group',
                'width' => '550px',
                'height' => '350px',
            ]),
            table_action_helper('modal', [
                'title' => 'MFA绑定',
                'icon' => 'fa fa-google-plus',
                'route' => 'admin/manager/mfa-bind',
                'width' => '780px',
                'height' => '700px',
                'params' => ['id']
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
                $model->google_key = \app\extend\Extend::googleAuth()->createSecret();
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
                //'parent' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                //    'label' => '我的上级',
                //    'placeholder' => '请填写我的上级用户名',
                //    'required' => false,
                //]),
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
                    'options' => AreaCode::areaCodes(AreaCode::STATUS_NORMAL),
                ]),
                'mobile' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '手机号',
                    'placeholder' => '请填写手机号',
                ]),
                'group' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '管理组',
                    'placeholder' => '请选择管理组',
                    'options' => AuthGroups::query('name')->indexBy('id')->column(),
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
                'photo' => form_fields_helper(FieldsOptions::CONTROL_FILE, [
                    'label' => '头像',
                    'placeholder' => '请上传头像',
                    'default' => $identify->photo,
                    'defaultLink' => attach_url($identify->photo),
                    'required' => false,
                    'comment' => '管理员头像，用于展示',
                    'number' => 1,

                    'fileScenario' => Uploads::SCENARIO_IMAGE,
                    'fileType' => '管理员列表',
                    'saveDirectory' => 'admin_user',
                    'pathPrefix' => $identify->id,
                    'layouts' => '8',
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
            $model = AdminUser::activeQuery(['id', 'username', 'password', 'email', 'an', 'mobile', 'photo'])->where(['id' => $bodyParams['id']])->one();
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
                $idMap = explode(',', $model->id);

                $groupMap = AdminUser::query('group')->where(['in', 'id', $idMap])->column();
                if (ArrayHelper::isIn(AuthGroups::ADMINISTRATOR_GROUP, $groupMap)) {
                    return $this->asFail(t('Disable the super administrator', 'app.admin'));
                }

                $result = AdminUser::updateAll([
                    'status' => $model->action == 'disabled' ? AdminUser::STATUS_DENY : AdminUser::STATUS_NORMAL,
                    'deny_end_time' => $model->action == 'disabled' ? $model->deny_end_time : null,
                ], ['in', 'id', $idMap]);
                return $result ? $this->asSuccess(t('operate successfully', 'app.admin')) : $this->asSuccess(t('operation failure', 'app.admin'));
            } else {
                return $this->asFail($model->error);
            }
        }
    }

    /**
     * 更改管理组
     * @param int $id
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionGroup($id)
    {
        if ($this->isGet) {
            $identify = AdminUser::findIdentity($id);
            if (empty($identify)) {
                return $this->asFail(t('Administrator does not exist', 'app.admin'));
            }

            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $identify->id,
                ]),
                'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '管理员名称',
                    'default' => $identify->username,
                    'required' => false,
                    'attribute' => ['disabled' => true],
                ]),
                'group' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '管理组',
                    'placeholder' => '请选择管理组',
                    'default' => $identify->group,
                    'options' => AuthGroups::query('name')->indexBy('id')->column(),
                ]),
            ];

            return $form->render($this);
        } else {
            $bodyParams = $this->post;
            /* @var AdminUser $model */
            $model = AdminUser::activeQuery(['id', 'group', 'password'])->where(['id' => $bodyParams['id']])->one();
            if (empty($model)) {
                return $this->asFail(t('Administrator does not exist', 'app.admin'));
            }

            if ($model->group === AuthGroups::ADMINISTRATOR_GROUP) {
                return $this->asFail(t('The super administrator cannot be changed', 'app.admin'));
            }

            $model->setScenario('update-group');
            if ($model->load($bodyParams)) {
                if ($model->save()) {

                    // 更新快捷菜单
                    $group = $model->group;
                    $canMap = AuthRelations::query('menu_id')
                        ->where(['group_id' => $group])
                        ->column();
                    $ownMap = AdminUserQuickAction::query('menu_id')
                        ->where(['admin_id' => $bodyParams['id']])
                        ->column();
                    $diff = array_diff($ownMap, $canMap);
                    if (!empty($diff)) {
                        AdminUserQuickAction::deleteAll([
                            'admin_id' => $bodyParams['id'],
                            'menu_id' => array_unique($diff),
                        ]);
                    }

                    return $this->asSuccess(t('submitted successfully', 'app.admin'));
                }

                return $this->asFail($model->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));
        }
    }

    /**
     * 管理员MFA绑定
     * @param int $id 管理员ID
     * @return string
     */
    public function actionMfaBind($id)
    {
        /* @var AdminUser $identify */
        $identify = AdminUser::findOne($id);

        $accountName = Yii::$app->params['admin_title_en'] . ':' . $identify->username;
        $qrcodeUrl = Extend::googleAuth()->getQRCodeGoogleUrl(
            $accountName,
            $identify->google_key,
            Yii::$app->params['admin_title']
        );

        $this->setLayoutViewPath();

        return $this->render('/home/mfa_bind', [
            'titleName' => null,
            'qrcodeUrl' => $qrcodeUrl,
            'accountName' => $accountName,
            'googleKey' => $identify->google_key,
        ]);
    }
}