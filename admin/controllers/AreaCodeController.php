<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;
use app\builder\form\FieldsOptions;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\models\AreaCode;

/**
 * 手机区号
 * @author cleverstone
 * @since ym1.0
 */
class AreaCodeController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index'     => ['get'],
        'add'       => ['get', 'post'],
        'edit'      => ['get', 'post'],
        'toggle'    => ['get', 'post'],
        'delete'    => ['get', 'post'],
    ];

    /**
     * 手机区号管理列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $table = ViewBuilder::table();
        // 标题
        $table->title = '手机区号管理';
        // 表格列
        $table->columns = [
            'name' => table_column_helper('地域名', ['style' => ['min-width' => '150px']]),
            'name_en' => table_column_helper('英文地域名', ['style' => ['min-width' => '150px']]),
            'code' => table_column_helper('电话区号', ['style' => ['min-width' => '150px']]),
            'status_label' => table_column_helper('状态', ['style' => ['min-width' => '120px']], function ($item) {
                return AreaCode::statusLabel($item['status']);
            }),
            'created_at' => table_column_helper('创建时间', ['style' => ['min-width' => '180px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '180px']]),
        ];
        // Sql
        $table->query = function () {
            $queryParams = $this->get;
            $query = AreaCode::activeQuery(['id', 'name', 'name_en', 'code', 'status', 'created_at', 'updated_at']);
            if (!empty($queryParams)) {
                $query->filterWhere([
                    'and',
                    ['like', 'name', isset($queryParams['name']) ? $queryParams['name'] : null],
                    ['like', 'code', isset($queryParams['code']) ? $queryParams['code'] : null],
                ]);
            }

            return $query;
        };
        // 行操作
        $table->rowActions = [
            // 编辑
            table_action_helper('modal', [
                'title' => '编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/area-code/edit',
                'width' => '550px',
                'height' => '350px',
            ]),
        ];
        // 工具栏 - 刷新
        $table->toolbarRefresh = [];
        // 工具栏 - 筛选
        $table->toolbarFilter = [
            'columns' => [
                'name' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '地域名',
                    'placeholder' => '请输入地域名',
                ]),
                'code' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '电话区号',
                    'placeholder' => '请输入电话区号',
                ]),
            ],
        ];
        // 工具栏 - 自定义
        $table->toolbarCustom = [
            table_toolbar_custom_helper('left', [
                'title' => '新增',
                'icon' => 'glyphicon glyphicon-plus',
                'option' => 'modal',
                'width' => '550px',
                'height' => '350px',
                'route' => 'admin/area-code/add',
            ]),
            table_toolbar_custom_helper('left', [
                'title' => '启用',
                'icon' => 'glyphicon glyphicon-ok-circle',
                'option' => 'ajax',
                'method' => 'POST',
                'route' => 'admin/area-code/toggle',
                'params' => ['id', 'action' => 'enabled'],
            ]),
            table_toolbar_custom_helper('left', [
                'title' => '禁用',
                'icon' => 'glyphicon glyphicon-ban-circle',
                'option' => 'ajax',
                'method' => 'POST',
                'route' => 'admin/area-code/toggle',
                'params' => ['id', 'action' => 'disabled'],
            ]),
        ];
        // Order by
        $table->orderBy = ['id' => SORT_DESC];

        return $table->render($this);
    }

    /**
     * 新增
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionAdd()
    {
        $model = new AreaCode();
        if ($this->isPost) {
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
                    'label' => $model->getAttributeLabel('name'),
                    'placeholder' => '请填写地域名称',
                    'required' => true,
                ]),
                'name_en' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => $model->getAttributeLabel('name_en'),
                    'placeholder' => '请填写英文地域名称',
                    'required' => true,
                ]),
                'code' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => $model->getAttributeLabel('code'),
                    'placeholder' => '请填写电话区号',
                    'required' => true,
                ]),
            ];

            return $form->render($this);
        }
    }

    /**
     * 编辑
     * @param int $id 主键ID
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionEdit($id)
    {
        if ($this->isGet) {
            $model = AreaCode::findOne($id);
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
                    'label' => '地域名称',
                    'placeholder' => '请填写地域名称',
                    'default' => $model->name,
                    'required' => true,
                ]),
                'name_en' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => 'name_en',
                    'placeholder' => '请填写英文地域名',
                    'default' => $model->name_en,
                    'required' => true,
                ]),
                'code' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '电话区号',
                    'placeholder' => '请填写电话区号',
                    'default' => $model->code,
                    'required' => true,
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
            $model = AreaCode::activeQuery(['id', 'name', 'name_en', 'code'])->where(['id' => $bodyParams['id']])->one();
            if (empty($model)) {
                return $this->asFail(t('data is empty', 'app.admin'));
            }

            // 设置验证场景
            $model->setScenario('edit');
            // 不更新[[id]]
            unset($bodyParams['id']);
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
        $model = new AreaCode();
        $model->setScenario('toggle');
        if ($model->load($bodyParam)) {
            if ($model->validate()) {
                $status = $model->action == 'disabled' ? AreaCode::STATUS_DENY : AreaCode::STATUS_NORMAL;
                AreaCode::updateAll([
                    'status' => $status,
                    'updated_at' => now(),
                ], ['in', 'id', explode(',', $model->id)]);

                return $this->asSuccess(t('submitted successfully', 'app.admin'));
            }

            return $this->asFail($model->error);
        }

        return $this->asFail(t('request parameter loading failed', 'app.admin'));
    }
}