<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use yii\log\Logger;
use app\models\SystemLog;
use app\builder\ViewBuilder;
use app\builder\helper\DateSplitHelper;
use app\builder\common\CommonController;
use app\builder\table\ToolbarFilterOptions;

/**
 * 系统日志
 * @author cleverstone
 * @since ym1.0
 */
class SystemLogController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 系统日志列表（即yii框架和后台日志）
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->title = '系统日志';
        $table->query = function () use ($queryParams) {
            $query = SystemLog::activeQuery([
                'id',
                'level',            // 日志等级
                'category',         // 日志种类
                'log_time',         // 记录时间
                'prefix',           // 请求主体
                'message',          // 内容消息
            ]);
            if (!empty($queryParams)) {
                $startAt = null;
                $endAt = null;
                if (isset($queryParams['log_time'])) {
                    list($startAt, $endAt) = DateSplitHelper::create($queryParams['log_time'])->timestamp()->toArray();
                }

                $query->filterWhere([
                    'and',
                    ['=', 'level', isset($queryParams['level']) ? $queryParams['level'] : null],
                    // 关键词筛选
                    ['like', 'category', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                    // 记录时间筛选
                    ['between', 'log_time', $startAt, $endAt],
                ]);
            }

            return $query;
        };

        $table->hideCheckbox = true;
        $table->columns = [
            'id' => table_column_helper('ID', ['style' => ['min-width' => '80px']]),
            'log_time' => table_column_helper('执行时间', ['style' => ['min-width' => '150px']], function ($item) {
                return date('Y-m-d H:i:s', floor($item['log_time']));
            }),
            'category' => table_column_helper('日志种类', ['style' => ['min-width' => '140px']]),
            'level' => table_column_helper('日志等级', ['style' => ['min-width' => '100px']], function ($item) {
                return html_label(Logger::getLevelName($item['level']), true, 'default');
            }),
            'prefix' => table_column_helper('请求主体', [], function ($item) {
                return $item['prefix'];
            }),
            'message' => table_column_helper('内容消息', [], function ($item) {
                return html_modal(x_highlight_string($item['message']));
            }),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '日志种类',
                    'placeholder' => '请输入日志种类',
                ]),
                'level' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '日志等级',
                    'placeholder' => '请选择日志等级',
                    'options' => [
                        Logger::LEVEL_ERROR => 'error',
                        Logger::LEVEL_WARNING => 'warning',
                        Logger::LEVEL_INFO => 'info',
                        Logger::LEVEL_TRACE => 'trace',
                        Logger::LEVEL_PROFILE_BEGIN => 'profile begin',
                        Logger::LEVEL_PROFILE_END => 'profile end',
                        Logger::LEVEL_PROFILE => 'profile',
                    ],
                ]),
                'log_time' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '记录时间',
                    'placeholder' => '请选择记录时间',
                    'range' => 1,
                ]),
            ],
        ];

        return $table->render($this);
    }
}