<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\models\AreaCode;

/**
 * 手机区号
 * @author cleverstone
 * @since 1.0
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
            'code' => table_column_helper('电话区号', ['style' => ['min-width' => '150px']]),
            'status_label' => table_column_helper('电话区号', ['style' => ['min-width' => '120px']], function ($item) {
                return AreaCode::statusLabel($item['status']);
            }),
            'created_at' => table_column_helper('创建时间', ['style' => ['min-width' => '180px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '180px']]),
        ];
        // Sql
        $table->query = function () {
            $queryParams = $this->get;
            $query = AreaCode::query(['id', 'name', 'code', 'status', 'created_at', 'updated_at']);
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
            // 删除
            table_action_helper('ajax', [
                'title' => '删除',
                'icon' => 'glyphicon glyphicon-remove',
                'route' => 'admin/area-code/delete',
                'method' => 'post',
            ]),
            // 编辑
            table_action_helper('modal', [
                'title' => '编辑',
                'icon' => 'fa fa-pencil-square-o',
                'route' => 'admin/area-code/edit',
                'width' => '700px',
                'height' => '500px',
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
                'width' => '700px',
                'height' => '500px',
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

    public function actionAdd()
    {

    }

    public function actionEdit()
    {

    }

    public function actionToggle()
    {

    }

    public function actionDelete()
    {

    }
}