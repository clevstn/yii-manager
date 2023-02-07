<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use Yii;
use app\builder\common\CommonController;
use app\builder\form\FieldsOptions;
use app\builder\helper\DateSplitHelper;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\models\AuthGroups;
use app\models\AuthRelations;
use yii\helpers\Json;

/**
 * 管理组
 * @author cleverstone
 * @since ym1.0
 */
class GroupController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
        'add' => ['get', 'post'],
        'edit' => ['get', 'post'],
        'toggle' => ['post'],
        'dispatch' => ['get', 'post'],
    ];

    /**
     * 管理组列表
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
        $table->title = '管理组';
        // 查询
        $table->query = function () use ($queryParams) {
            $query = AuthGroups::activeQuery([
                'id',               // ID
                'name',             // 角色名
                'desc',             // 备注
                'is_enabled',       // 是否开启，0：禁用 1：正常
                'created_at',       // 创建时间
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
                    // 角色名筛选
                    ['like', 'name', isset($queryParams['name']) ? $queryParams['name'] : null],
                    // 状态筛选
                    ['is_enabled' => isset($queryParams['is_enabled']) ? $queryParams['is_enabled'] : null],
                    // 注册时间筛选
                    ['between', 'created_at', $startAt, $endAt],
                ]);
            }

            return $query;
        };
        $table->columns = [
            'id' => table_column_helper('ID', ['style' => ['min-width' => '88px']]),
            'name' => table_column_helper('角色名', ['style' => ['min-width' => '120px']]),
            'desc' => table_column_helper('备注', ['style' => ['min-width' => '150px']]),
            'is_enabled' => table_column_helper('角色状态', ['style' => ['min-width' => '120px']], function ($item) {
                return AuthGroups::getStatusLabel($item['is_enabled']);
            }),
            'is_dispatched' => table_column_helper('是否分配权限', ['style' => ['min-width' => '130px']], function ($item) {
                return AuthRelations::findOne(['group_id' => $item['id']]) ? html_label('已分配') : html_label('未分配', true, 'default');
            }),
            'created_at' => table_column_helper('注册时间', ['style' => ['min-width' => '150px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'name' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '角色名',
                    'placeholder' => '请输入角色名',
                ]),
                'is_enabled' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '角色状态',
                    'placeholder' => '请选择角色状态',
                    'options' => [
                        AuthGroups::STATUS_DENY => '已禁用',
                        AuthGroups::STATUS_NORMAL => '正常',
                    ],
                ]),
                'created_at' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '创建时间',
                    'placeholder' => '请选择创建时间',
                    'range' => 1,
                ]),
            ],
        ];
        // 自定义工具栏
        $table->toolbarCustom = [
            // 新增
            table_toolbar_custom_helper('left', [
                'title' => '新增',
                'icon' => 'fa fa-plus',
                'option' => 'modal',
                'route' => 'admin/group/add',
                'width' => '550px',
                'height' => '300px',
            ]),
            // 禁用
            table_toolbar_custom_helper('left', [
                'title' => '禁用',
                'icon' => 'fa fa-lock',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/group/toggle',
                'params' => ['id', 'action' => 'disabled'],
            ]),
            // 启用
            table_toolbar_custom_helper('left', [
                'title' => '启用',
                'icon' => 'fa fa-unlock',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/group/toggle',
                'params' => ['id', 'action' => 'enabled'],
            ]),
        ];
        // 行操作
        $table->rowActions = [
            table_action_helper('modal', [
                'title' => '基本编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/group/edit',
                'width' => '550px',
                'height' => '330px',
            ]),
            table_action_helper('modal', [
                'title' => '分配权限',
                'icon' => 'fa fa-users',
                'route' => 'admin/group/dispatch',
                'width' => '100%',
                'height' => '100%',
                'params' => ['id'],
            ]),
        ];

        return $table->render($this);
    }

    /**
     * 新增管理组
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionAdd()
    {
        $this->emptyStrToNull = false;

        if ($this->isPost) {
            $model = new AuthGroups();
            $model->setScenario('add');
            if ($model->load($this->post)) {
                if ($model->save()) {
                    return $this->asSuccess(t('submitted successfully', 'app.admin'));
                }

                return $this->asFail($model->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));
        } else {
            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'name' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '角色名',
                    'placeholder' => '请填写角色名',
                    'required' => true,
                ]),
                'desc' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '备注',
                    'placeholder' => '请填写备注',
                    'required' => false,
                ]),
            ];

            return $form->render($this);
        }
    }

    /**
     * 编辑
     * @param string $id 主键ID
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionEdit($id)
    {
        $this->emptyStrToNull = false;
        if ($this->isGet) {
            $model = AuthGroups::findOne($id);
            if (empty($model)) {
                return $this->asFail(t('data is empty', 'app.admin'));
            }

            // 渲染表单
            $form = ViewBuilder::form();
            $form->partial = true;
            $form->backBtn = false;
            $form->fields = [
                'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $model->id,
                ]),
                'name' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '角色名',
                    'placeholder' => '请填写角色名',
                    'default' => $model->name,
                    'required' => true,
                ]),
                'desc' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '备注',
                    'placeholder' => '请填写备注',
                    'default' => $model->desc,
                    'required' => false,
                ]),
            ];

            return $form->render($this);
        } else {
            // 提交编辑
            $bodyParams = $this->post;
            // 参数校验
            if (empty($bodyParams['id'])) {
                return $this->asFail(t('parameter error', 'app.admin'));
            }

            // 查询校验
            /* @var AuthGroups|null $model */
            $model = AuthGroups::activeQuery(['id', 'name', 'desc'])->where(['id' => $bodyParams['id']])->one();
            if (empty($model)) {
                return $this->asFail(t('data is empty', 'app.admin'));
            }

            // 设置验证场景
            $model->setScenario('edit');
            // 数据校验
            if ($model->load($bodyParams)) {
                if ($model->save()) {
                    return $this->asSuccess(t('submitted successfully', 'app.admin'));
                }

                return $this->asFail($model->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));
        }
    }

    /**
     * 禁用/启用
     * @return string
     */
    public function actionToggle()
    {
        // `enabled`和`disabled`提交表单
        $bodyParam = $this->post;
        $model = new AuthGroups();
        $model->setScenario('toggle');
        if ($model->load($bodyParam)) {
            if ($model->validate()) {
                $status = $model->action == 'disabled' ? AuthGroups::STATUS_DENY : AuthGroups::STATUS_NORMAL;
                AuthGroups::updateAll([
                    'is_enabled' => $status,
                    'updated_at' => now(),
                ], ['in', 'id', explode(',', $model->id)]);

                return $this->asSuccess(t('submitted successfully', 'app.admin'));
            }

            return $this->asFail($model->error);
        }

        return $this->asFail(t('request parameter loading failed', 'app.admin'));
    }

    /**
     * 管理组权限分配
     * @param int $id 组ID
     * @return string
     */
    public function actionDispatch($id)
    {
        if ($this->isPost) {
            $bodyParams = $this->post;
            if (empty($bodyParams['menuIds'])) {
                return $this->asFail(t('data is empty', 'app.admin'));
            }

            if (!is_array($bodyParams['menuIds'])) {
                return $this->asFail(t('parameter type error', 'app.admin'));
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // 先删除所有权限
                AuthRelations::deleteAll(['group_id' => $id]);

                // 重新添加权限
                foreach ($bodyParams['menuIds'] as $menuId) {
                    $model = new AuthRelations();
                    $model->group_id = $id;
                    $model->menu_id = $menuId;
                    if (!$model->save()) {
                        $transaction->rollBack();
                        return $this->asFail($model->error);
                    }
                }

                $transaction->commit();

                /* 清楚指定组权限缓存 */
                Yii::$app->rbacManager->invalidateSpecialCache($id);

                return $this->asSuccess();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->asFail($e->getMessage());
            }

        } else {
            // 获取系统权限列表
            $permissions = Yii::$app->rbacManager->getPermissionsByGroupFromDb(AuthGroups::ADMINISTRATOR_GROUP);
            // 获取指定角色可以访问的菜单ID
            $ownedPers = AuthRelations::activeQuery(['menu_id'])->where(['group_id' => $id])->asArray()->column();
            // Permission Trees
            $permissionTrees = AuthRelations::getMarkOwnedPermissionTrees($permissions, $ownedPers);
            $this->setLayoutViewPath();

            return $this->render('dispatch', ['data' => Json::encode($permissionTrees)]);
        }
    }
}