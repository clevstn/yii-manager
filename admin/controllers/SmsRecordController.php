<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\helper\DateSplitHelper;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\models\AdminUser;
use app\models\ShortMsgRecord;

/**
 * 短信记录
 * @author cleverstone
 * @since ym1.0
 */
class SmsRecordController extends \app\builder\common\CommonController
{
    /**
     * @var array
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 短信记录列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->title = '短信记录';
        $table->query = function () use ($queryParams) {
            $query = ShortMsgRecord::query([
                'id',
                'service_name',      // 服务名称
                'msg_content',       // 内容
                'auth_code',         // 认证码
                'send_user',         // 发送人
                'receive_mobile',    // 接收手机号
                'send_time',         // 发送时间
            ]);
            if (!empty($queryParams)) {
                $startAt = null;
                $endAt = null;
                if (isset($queryParams['send_time'])) {
                    list($startAt, $endAt) = DateSplitHelper::create($queryParams['send_time'])->reformat()->toArray();
                }

                $query->filterWhere([
                    'and',
                    // 关键词筛选
                    [
                        'or',
                        ['like', 'service_name', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'send_user', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'receive_mobile', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                    ],
                    // 发送时间筛选
                    ['between', 'send_time', $startAt, $endAt],
                ]);
            }

            return $query;
        };

        $table->hideCheckbox = true;
        $table->columns = [
            'id' => table_column_helper('ID', ['style' => ['min-width' => '80px']]),
            'service_name' => table_column_helper('服务种类', ['style' => ['min-width' => '130px']]),
            'send_user' => table_column_helper('发送人', ['style' => ['min-width' => '140px']], function ($item) {
                if (!$item['send_user']) {
                    return 'System';
                }

                $one = AdminUser::query('username,email,mobile')->where(['id' => $item['send_user']])->one();
                if (empty($one)) {
                    return '--';
                }

                return "[{$one['username']}][{$one['email']}][{$one['mobile']}]";
            }),
            'receive_mobile' => table_column_helper('接收手机号', ['style' => ['min-width' => '100px']]),
            'auth_code' => table_column_helper('认证码', ['style' => ['min-width' => '80px']], function ($item) {
                return $item['auth_code'] ?: '--';
            }),
            'msg_content' => table_column_helper('短信内容', [], function ($item) {
                return html_popover($item['msg_content'], '内容详情', '查看详情');
            }),
            'send_time' => table_column_helper('发送时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '关键词',
                    'placeholder' => '请输入服务名称/发送人/接收手机号',
                ]),
                'send_time' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '发送时间',
                    'placeholder' => '请选择发送时间',
                    'range' => 1,
                ]),
            ],
        ];

        return $table->render($this);
    }
}
