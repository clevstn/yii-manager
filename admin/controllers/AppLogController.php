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
use app\models\AppLog;

/**
 * 应用日志记录
 * @author cleverstone
 * @since ym1.0
 */
class AppLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 应用日志列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $queryParams = $this->get;
        $table = ViewBuilder::table();
        $table->title = '应用日志';
        $table->query = function () use ($queryParams) {
            $query = AppLog::activeQuery([
                'id',
                'subject',          // 日志主题
                'log_level',        // 日志等级
                'params_content',   // 执行参数
                'result_content',   // 返回结果
                'created_at',       // 执行时间
            ]);
            if (!empty($queryParams)) {
                $startAt = null;
                $endAt = null;
                if (isset($queryParams['created_at'])) {
                    list($startAt, $endAt) = DateSplitHelper::create($queryParams['created_at'])->reformat()->toArray();
                }

                $query->filterWhere([
                    'and',
                    // 关键词筛选
                    [
                        'or',
                        ['like', 'subject', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                        ['like', 'log_level', isset($queryParams['keyword']) ? $queryParams['keyword'] : null],
                    ],
                    // 操作时间筛选
                    ['between', 'created_at', $startAt, $endAt],
                ]);
            }

            return $query;
        };

        $table->hideCheckbox = true;
        $table->columns = [
            'id' => table_column_helper('ID', ['style' => ['min-width' => '80px']]),
            'created_at' => table_column_helper('执行时间', ['style' => ['min-width' => '150px']]),
            'subject' => table_column_helper('日志主题', ['style' => ['min-width' => '140px']]),
            'log_level' => table_column_helper('日志等级', ['style' => ['min-width' => '100px']], function ($item) {
                return html_label($item['log_level'], true, 'default');
            }),
            'params_content' => table_column_helper('执行参数', [], function ($item) {
                return html_popover($item['params_content']);
            }),
            'result_content' => table_column_helper('返回结果', [], function ($item) {
                return html_popover($item['result_content']);
            }),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'keyword' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_TEXT,
                    'label' => '日志主题/等级',
                    'placeholder' => '请输入日志主题/日志等级',
                ]),
                'created_at' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_DATE,
                    'label' => '操作时间',
                    'placeholder' => '请选择操作时间',
                    'range' => 1,
                ]),
            ],
        ];

        return $table->render($this);
    }
}