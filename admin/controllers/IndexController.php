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
use app\builder\form\FieldsOptions;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;
use app\builder\table\widgets\SelectConnection;

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
        $params = $this->get;
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
        $tableBuilder->query = function () use ($params) {
            $start = null;
            $end = null;
            if (!empty($params['created_at'])) {
                list($start, $end) = DateSplitHelper::create($params['created_at'])->reformat()->toArray();;
            }

            $query = AdminUser::find()
                ->filterWhere([
                    'and',
                    ['between', 'created_at', $start, $end],
                    ['status' => isset($params['status']) ? $params['status'] : null],
                    [
                        'or',
                        ['like', 'username',  isset($params['keyword']) ? $params['keyword'] : null],
                        ['like', 'email', isset($params['keyword']) ? $params['keyword'] : null],
                    ]
                ])
                ->select([
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
                'height' => '90%',
            ]),
            table_action_helper('page', [
                'title' => '新增',
                'icon' => 'fa fa-plus',
                'route' => 'admin/index/add',
            ]),
        ];
        $tableBuilder->toolbarRefresh = [];
        $tableBuilder->toolbarFilter = [
            'title' => '',
            'icon' => '',
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_TEXT,
                    'label'         => '关键词',
                    'placeholder'   => '请填写关键词',
                ]),
                /*'email' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_NUMBER,
                    'label'         => '数字',
                    'placeholder'   => '请填写数字',
                ]),*/
                'created_at' => table_toolbar_filter_helper([
                    'control'       => ToolbarFilterOptions::CONTROL_DATETIME,
                    'label'         => '注册时间',
                    'range'         => 1,
                    'placeholder'   => '请选择注册时间',
                ]),
                /*'date' => table_toolbar_filter_helper([
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
                ]),*/
                'status' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label'         => '状态',
                    'placeholder'   => '请选择状态',
                    'default'       => '',
                    'options' => [
                        '0' => '封停',
                        '1' => '正常',
                    ],
                ]),
                /*'custom' => table_toolbar_filter_helper([
                    'control' => 'custom',
                    'widget'  => new SelectConnection(),
                ]),*/
            ],
        ];
        $tableBuilder->toolbarExport = [
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
                'params'    => ['id', 'status'],
            ]),
            table_toolbar_custom_helper('left', [
                'title'     => '新增',
                'icon'      => 'glyphicon glyphicon-plus',
                'option'    => 'modal',
                'height'    => '90%',
                'route'     => 'admin/index/edit',
            ]),
            table_toolbar_custom_helper('left', [
                'title'     => '页面',
                'icon'      => 'glyphicon glyphicon-list-alt',
                'option'    => 'page',
                'params'    => ['id', 'status'],
                'route'     => 'admin/index/edit',
            ]),
        ];

        return $tableBuilder->render($this);
    }

    /**
     * 新增
     * @return string
     * @throws \Throwable
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionAdd()
    {
        if ($this->isPost) {
            return $this->asSuccess();
        }
        $formBuilder = ViewBuilder::form();
        $formBuilder->partial = false;
        $formBuilder->backBtn = true;
        $formBuilder->autoBack = true;
        $formBuilder->title = '新增';
        $formBuilder->fields = [
            'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '用户名',
                'placeholder' => '请输入用户名',
                'default' => 'cleverstone',
                'comment' => '用户名必须和登记姓名一致。',
            ]),
            'number' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                'label' => '编号',
                'placeholder' => '请输入编号',
                'default' => '1005452',
                'comment' => '编号必须是登记时的有效编号。',
                'attribute' => "",
            ]),
            'password' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                'label' => '密码',
                'default' => '123456',
                'comment' => '请输入6-18位有效密码。',
                'placeholder' => '请输入密码',
            ]),
            'mark' => form_fields_helper(FieldsOptions::CONTROL_TEXTAREA, [
                'label' => '备注',
                'default' => '这是默认值',
                'comment' => '请输入您认为有效的默认值。',
                'placeholder' => '请填写备注，字数范围5-250',
            ]),
            'country' => form_fields_helper(FieldsOptions::CONTROL_CHECKBOX, [
                'label' => '国家',
                'default' => '2,3,4',
                'options' => [
                    '中国' => 1,
                    '美国' => 2,
                    '日本' => 3,
                    '意大利' => 4,
                    '德国' => 5,
                    '英国' => 6,
                    '俄罗斯' => 7,
                    '乌克兰' => 8,
                    '新加坡' => 9,
                    '马来西亚' => 10,
                    '老挝' => 11,
                    '缅甸' => 12,
                    '新疆' => 13,
                    '韩国' => 14,
                    '伊拉克' => 15,
                ]
            ]),
            'sex' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                'label' => '性别',
                'default' => 2,
                'options' => [
                    '男' => 0,
                    '女' => 1,
                    '未知' => 2,
                ],
            ]),
            'status' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                'label' => '状态',
                'placeholder' => '请选择状态',
                'default' => 2,
                'options' => [
                    '禁用' => 0,
                    '启用' => 2,
                ],
            ]),
            'create_at' => form_fields_helper(FieldsOptions::CONTROL_TIME, [
                'label' => '创建时间',
                'default' => '02:00:00 / 03:00:00',
                'placeholder' => '请选择创建时间',
                'range' => 1,
            ]),
            'photo' => form_fields_helper(FieldsOptions::CONTROL_FILE, [
                'label' => '头像上传',
                'default' => '',
                'number' => 1,
            ]),
            'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                'default' => 1,
            ]),
            'notice' => form_fields_helper(FieldsOptions::CONTROL_RICHTEXT, [
                'label' => '公告',
                'default' => '<p>请填写公告内容</p>',
            ])
        ];

        return $formBuilder->render($this);
    }

    /**
     * 编辑
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function actionEdit()
    {
        if ($this->isPost) {
            return $this->asSuccess();
        }

        $formBuilder = ViewBuilder::form();
        $formBuilder->partial = true;
        $formBuilder->backBtn = false;
        $formBuilder->fields = [
            'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '用户名',
                'placeholder' => '请输入用户名',
            ]),
            'number' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                'label' => '编号',
                'placeholder' => '请输入编号',
                'attribute' => [
                    'max' => 1000,
                    'min' => 1,
                ]
            ]),
            'password' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                'label' => '密码',
                'placeholder' => '请输入密码',
            ]),
            'mark' => form_fields_helper(FieldsOptions::CONTROL_TEXTAREA, [
                'label' => '备注',
                'placeholder' => '请填写备注，字数范围5-250',
            ]),
            'country' => form_fields_helper(FieldsOptions::CONTROL_CHECKBOX, [
                'label' => '国家',
                'options' => [
                    '中国' => 1,
                    '美国' => 2,
                    '日本' => 3,
                    '意大利' => 4,
                    '德国' => 5,
                ]
            ]),
            'sex' => form_fields_helper(FieldsOptions::CONTROL_RADIO, [
                'label' => '性别',
                'options' => [
                    '男' => 0,
                    '女' => 1,
                    '未知' => 2,
                ],
            ]),
            'status' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                'label' => '状态',
                'placeholder' => '请选择状态',
                'options' => [
                    '禁用' => 0,
                    '启用' => 2,
                ],
            ]),
            'photo' => form_fields_helper(FieldsOptions::CONTROL_FILE, [
                'label' => '头像上传',
                'number' => 5,
            ]),
            'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                'default' => 1,
            ]),
            'notice' => form_fields_helper(FieldsOptions::CONTROL_RICHTEXT, [
                'label' => '公告',
                'default' => '请填写公告内容',
            ])
        ];

        return $formBuilder->render($this);
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

        $idMap = explode(',', $bodyParams['id']);
        $statusMap = array_map(function ($value) {
            return $value == 0 ? 1 : 0;
        }, explode(',', $bodyParams['status']));

        $dataMap = array_combine($idMap, $statusMap);
        foreach ($dataMap as $id => $status) {
            $model = AdminUser::findOne(['id' => $id]);
            $model->status = $status;
            $model->save();
        }

        return $this->asSuccess([], '操作成功');
    }
}
