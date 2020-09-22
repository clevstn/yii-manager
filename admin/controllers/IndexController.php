<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use app\models\AdminUser;
use app\builder\ViewBuilder;
use app\builder\table\Table;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;

/**
 * 首页
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IndexController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
        'add' => ['get', 'post'],
        'edit' => ['get', 'post'],
        'disable' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
        'add',
        'edit',
        'disable',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [];

    /**
     * Renders the index view for the module
     * @return string
     * @throws \yii\base\NotSupportedException
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionIndex()
    {
        $tableBuilder = ViewBuilder::table();
        $tableBuilder->title = '首页';
        /*$tableBuilder->widget = [
            Table::TABLE_TOOL_TOP => '<p style="padding:10px;padding-bottom:0;">这里是工具栏头部</p>',
            Table::TABLE_TOOL_BOTTOM => '<p style="padding:10px;padding-bottom:0;">这里是工具栏底部</p>',
            Table::TABLE_PAGE_TOP => '<p style="padding:10px;padding-bottom:0;">这里是分页头部</p>',
            Table::TABLE_PAGE_BOTTOM => '<p style="padding:10px;padding-bottom:0;">这里是分页底部</p>',
        ];*/
        $tableBuilder->columns = [
            'username' => table_column_helper('用户名', ['style' => ['min-width' => '100px']]),
            'email' => table_column_helper('邮箱', ['style' => ['min-width' => '200px']]),
            'an_mobile' => table_column_helper('电话', ['style' => ['min-width' => '100px']], function ($item) {
                return '+' . $item['an'] . ' ' . $item['mobile'];
            }),
            'status_label' => table_column_helper('状态', ['style' => ['min-width' => '80px']], function ($item) {
                switch ($item['status']){
                    case 0:
                        return '<span class="label label-danger">已封停</span>';
                    case 1:
                        return '<span class="label label-success">正常</span>';
                    default:
                        return '<span class="label label-default">未知</span>';
                }
            }),
            'identify_code' => table_column_helper('邀请码', ['style' => ['min-width' => '100px']]),
            'created_at' => table_column_helper('注册时间', ['style' => ['min-width' => '180px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '180px']]),
        ];
        $tableBuilder->query = function () {
            $query = AdminUser::find()->select([
                'id',
                'username',
                'email',
                'an',
                'mobile',
                'status',
                'identify_code',
                'created_at',
                'updated_at',
            ]);
            return $query;
        };
        $tableBuilder->orderBy = 'id DESC';
        $tableBuilder->primaryKey = 'id';
        $tableBuilder->page = true;
        $tableBuilder->hideCheckbox = false;
        $tableBuilder->rowActions = [
            table_action_helper('ajax', [
                'title' => '解封/封停',
                'icon' => 'fa fa-lock',
                'route' => 'admin/index/disable',
                'params' => ['id', 'status'],
                'method' => 'post',
            ]),
            //table_action_helper('division', []),
            table_action_helper('modal', [
                'title' => '编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/index/edit',
                'width' => '60%',
                'height' => '80%',
            ]),
            table_action_helper('page', [
                'title' => '新增',
                'icon' => 'fa fa-plus',
                'route' => 'admin/index/add',
            ]),
        ];
        $tableBuilder->toolbarRefresh = [];
        $tableBuilder->toolbarFilter = [
            'title' => '筛选',
            'icon' => '',
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_TEXT,
                    'label'         => '关键词',
                    'placeholder'   => '请填写关键词',
                ]),
                'email' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_NUMBER,
                    'label'         => '数字',
                    'placeholder'   => '请填写数字',
                ]),
                'create_time' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_DATETIME,
                    'label'         => '注册时间',
                    'range'         => 1,
                    'placeholder'   => '请选择注册时间',
                ]),
                'date' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_DATE,
                    'label'         => '日期',
                    'range'         => 1,
                    'placeholder'   => '请选择日期',
                ]),
                'year' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_YEAR,
                    'label'         => '年份',
                    'range'         => 1,
                    'placeholder'   => '请选择年份',
                ]),
                'month' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_MONTH,
                    'label'         => '月份',
                    'range'         => 1,
                    'placeholder'   => '请选择月份',
                ]),
                'time' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_TIME,
                    'label'         => '时间',
                    'range'         => 1,
                    'placeholder'   => '请选择时间',
                ]),
                'status' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label'         => '状态',
                    'placeholder'   => '请选择状态',
                    'default'       => '',
                    'options' => [
                        '1' => '正常',
                        '2' => '停用',
                    ],
                ]),
            ],
        ];
        $tableBuilder->toolbarExport = [
            'title' => '导出',
            'icon' => '',
            'name' => '会员列表',
            'heads' => ['ID', '用户名', '邮箱', '电话'],
            'fields' => ['id', 'username', 'email', 'an', 'mobile'],
            'columns' => [
                'id',
                'username',
                'email',
                'mobile' => function ($item) {
                    return '+' . $item['an'] . ' ' . $item['mobile'];
                },
            ],
        ];
        $tableBuilder->toolbarCustom = [
            table_toolbar_custom_helper('left', [
                'title'     => '禁用',
                'icon'      => 'glyphicon glyphicon-remove',
                'option'    => 'ajax',
                'method'    => 'POST',
                'route'     => 'admin/index/disable',
                'params'    => ['action' => 'disable', 'id', 'status'],
            ]),
            table_toolbar_custom_helper('left', [
                'title'     => '新增',
                'icon'      => 'glyphicon glyphicon-plus',
                'option'    => 'modal',
                'width'     => '60%',
                'height'     => '80%',
                'route'     => 'admin/index/edit',
            ]),
            table_toolbar_custom_helper('left', [
                'title'     => '页面',
                'icon'      => 'glyphicon glyphicon-list-alt',
                'option'    => 'page',
                'params'    => ['action' => 'disable', 'id', 'status'],
                'route'     => 'admin/index/edit',
            ]),
        ];

        return $tableBuilder->render($this);
    }

    /**
     * 新增
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionAdd()
    {
        return '新增';
    }

    /**
     * 编辑
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionEdit()
    {
        $tableBuilder = ViewBuilder::table();
        //$tableBuilder->title = '首页';
        $tableBuilder->partial = true;
        $tableBuilder->columns = [
            'username' => table_column_helper('用户名', ['style' => ['min-width' => '100px']]),
            'email' => table_column_helper('邮箱', ['style' => ['min-width' => '200px']]),
            'an_mobile' => table_column_helper('电话', ['style' => ['min-width' => '100px']], function ($item) {
                return '+' . $item['an'] . ' ' . $item['mobile'];
            }),
            'status_label' => table_column_helper('状态', ['style' => ['min-width' => '80px']], function ($item) {
                switch ($item['status']){
                    case 0:
                        return '<span class="label label-danger">已封停</span>';
                    case 1:
                        return '<span class="label label-success">正常</span>';
                    default:
                        return '<span class="label label-default">未知</span>';
                }
            }),
            'identify_code' => table_column_helper('邀请码', ['style' => ['min-width' => '100px']]),
            'created_at' => table_column_helper('注册时间', ['style' => ['min-width' => '180px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '180px']]),
        ];
        $tableBuilder->query = function () {
            $query = AdminUser::find();
            return $query;
        };
        $tableBuilder->orderBy = 'id DESC';
        $tableBuilder->primaryKey = 'id';
        $tableBuilder->page = true;
        $tableBuilder->hideCheckbox = true;
        $tableBuilder->rowActions = [
            table_action_helper('ajax', [
                'title' => '解封/封停',
                'icon' => 'fa fa-lock',
                'route' => 'admin/index/disable',
                'params' => ['id', 'status'],
                'method' => 'post',
            ]),
            //table_action_helper('division', []),
            table_action_helper('modal', [
                'title' => '编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/index/edit',
                'width' => '60%',
                'height' => '80%',
            ]),
            table_action_helper('page', [
                'title' => '新增',
                'icon' => 'fa fa-plus',
                'route' => 'admin/index/add',
            ]),
        ];

        return $tableBuilder->render($this);
    }

    /**
     * 禁用
     * @return mixed
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionDisable()
    {
        $bodyParams = $this->post;
        $id = $bodyParams['id'];
        $action = ($bodyParams['status'] == 0 ? 1 : 0);
        $model = AdminUser::findOne(['id' => $id]);
        $model->status = $action;
        $model->save();

        return $this->asSuccess([], '操作成功');
    }
}
