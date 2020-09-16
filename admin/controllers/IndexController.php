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
use app\builder\common\CommonController;

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
        return ViewBuilder::table()
            ->setTitle('首页')
            ->setColumns([
                'username' => table_column_helper('用户名', [
                    'style' => [
                        'width' => '100px',
                    ],
                ]),
                'email' => table_column_helper('邮箱'),
                'an_mobile' => table_column_helper('电话', [], function ($item) {
                    return '+' . $item['an'] . ' ' . $item['mobile'];
                }),
                'created_at' => table_column_helper('注册时间'),
                'updated_at' => table_column_helper('更新时间'),
            ])
            ->setQuery(function () {
                $query = AdminUser::find();
                return $query;
            })
            ->setOrderBy('id DESC')
            ->setPrimaryKey('id')
            ->setPage()
            ->setHideCheckbox(false)
            ->setRowActions([
                table_action_helper('ajax', [
                    'title' => '禁用',
                    'icon' => 'fa fa-lock',
                    'route' => 'admin/index/disable',
                    'params' => ['id', 'action' => 0],
                    'method' => 'post',
                ]),
                //table_action_helper('division', []),
                table_action_helper('modal', [
                    'title' => '编辑',
                    'icon' => 'fa fa-pencil-square-o',
                    'route' => 'admin/index/edit',
                    'params' => ['id'],
                ]),
                table_action_helper('page', [
                    'title' => '新增',
                    'icon' => 'fa fa-plus',
                    'route' => 'admin/index/add',
                    'params' => ['id'],
                ]),
            ])
            ->render($this);
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
        return '编辑';
    }

    /**
     * 禁用
     * @return mixed
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionDisable()
    {
        return $this->asSuccess([], '执行成功恭喜您，原因：您的审核条件过于优越，因此平台决定给您授权！');
    }
}
