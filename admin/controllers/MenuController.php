<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;
use app\builder\form\FieldsOptions;
use app\builder\ViewBuilder;
use app\models\AuthMenu;
use Yii;

/**
 * 菜单
 * @author cleverstone
 * @since ym1.0
 */
class MenuController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
        'update' => ['post'],
        'edit' => ['get', 'post'],
    ];

    /**
     * 菜单列表
     * @return string
     * @throws \Throwable
     */
    public function actionIndex()
    {
        // 表格构建器对象
        $table = ViewBuilder::table();
        // 表格页标题
        $table->title = '菜单列表';
        $table->page = false;
        $table->hideCheckbox = true;
        // 查询
        $table->query = function () {
            $data = AuthMenu::query()->orderBy(['sort' => SORT_ASC])->all();
            return AuthMenu::getFormatData($data);
        };
        $table->columns = [
            'sort' => table_column_helper('排序', ['style' => ['max-width' => '88px']]),
            'id' => table_column_helper('ID', ['style' => ['max-width' => '88px']]),
            'pid' => table_column_helper('PID', ['style' => ['max-width' => '88px']]),
            'label' => table_column_helper('名称', ['style' => ['min-width' => '200px']], function ($item) {
                return AuthMenu::getLabelTypeStr($item['label_type']) .
                    $item['_prefix'] .
                    (!empty($item['icon']) ? '<i class="' . $item['icon'] . '"></i>&nbsp;' : '') .
                    $item['label'];
            }),
            'src' => table_column_helper('路由', ['style' => ['min-width' => '88px']]),
            'dump_way' => table_column_helper('打开方式', ['style' => ['min-width' => '88px']]),
            'link_type' => table_column_helper('链接类型', ['style' => ['min-width' => '88px']], function ($item) {
                return AuthMenu::getLinkTypeStr($item['link_type']);
            }),
            'desc' => table_column_helper('备注', ['style' => ['min-width' => '150px']]),
            'created_at' => table_column_helper('注册时间', ['style' => ['min-width' => '150px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 自定义工具栏
        $table->toolbarCustom = [
            // 新增
            table_toolbar_custom_helper('left', [
                'title' => '更新菜单',
                'icon' => 'glyphicon glyphicon-open',
                'option' => 'ajax',
                'route' => 'admin/menu/update',
                'method' => 'post',
            ]),
        ];
        // 行操作
        $table->rowActions = [
            table_action_helper('modal', [
                'title' => '编辑',
                'icon' => 'glyphicon glyphicon-edit',
                'route' => 'admin/menu/edit',
                'width' => '550px',
                'height' => '330px',
                'params' => ['id'],
            ]),
        ];

        return $table->render($this);
    }

    /**
     * 从本地更新菜单列表
     * @return string
     * @throws \Exception
     */
    public function actionUpdate()
    {
        $result = Yii::$app->rbacManager->updateMenuItems();

        return $result === true ? $this->asSuccess() : $this->asFail($result);
    }

    /**
     * 编辑
     * @param int $id
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionEdit($id)
    {
        $this->emptyStrToNull = false;

        $model = AuthMenu::findOne($id);
        if ($this->isPost) {
            $model->setScenario('edit');
            if ($model->load($this->post)) {
                if ($model->save()) {
                    // 清空缓存
                    Yii::$app->rbacManager->invalidateCache();

                    return $this->asSuccess(t('submitted successfully', 'app.admin'));
                }

                return $this->asFail($model->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));
        } else {
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
                'sort' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '排序',
                    'placeholder' => '请排序',
                    'default' => $model->sort,
                    'required' => true,
                ]),
                'desc' => form_fields_helper(FieldsOptions::CONTROL_TEXTAREA, [
                    'label' => '备注',
                    'placeholder' => '请填写备注',
                    'default' => $model->desc,
                    'required' => false,
                ]),
            ];

            return $form->render($this);
        }
    }
}