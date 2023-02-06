<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;
use app\builder\helper\DateSplitHelper;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\models\AuthGroups;

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
                        AuthGroups::STATUS_DISABLED => '已禁用',
                        AuthGroups::STATUS_ENABLED => '正常',
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
                'height' => '350px',
            ]),
            // 封停
            table_toolbar_custom_helper('left', [
                'title' => '封停',
                'icon' => 'fa fa-lock',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/group/toggle',
                'params' => ['id', 'action' => 'disabled'],
            ]),
            // 解封
            table_toolbar_custom_helper('left', [
                'title' => '解封',
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
                'height' => '350px',
            ]),
            table_action_helper('modal', [
                'title' => '分配权限',
                'icon' => 'fa fa-users',
                'route' => 'admin/group/dispatch',
                'width' => '700px',
            ]),
        ];

        return $table->render($this);
    }

    public function actionAdd()
    {
        
    }

    public function actionEdit()
    {

    }

    public function actionToggle()
    {

    }

    public function actionDispatch()
    {

    }
}