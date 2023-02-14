<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\admin\controllers;

use Yii;
use app\builder\common\CommonController;
use app\builder\helper\DateSplitHelper;
use app\builder\table\ToolbarFilterOptions;
use app\builder\ViewBuilder;
use app\components\Uploads;
use app\models\Attachment;

/**
 * 附件
 * @author cleverstone
 * @since ym1.0
 */
class AttachmentController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get', 'post'],
        'list' => ['get'],
        'remove' => ['post'],
        'copy' => ['post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'list',
    ];

    /**
     * 列表
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $all = Attachment::query('type,bucket')
            ->all();

        $typeMap = [];
        $bucketMap = [];
        foreach ($all as $item) {
            $typeMap[$item['type']] = $item['type'];
            $bucketMap[$item['bucket']] = $item['bucket'];
        }

        $queryParams = $this->get;
        // 表格构建器对象
        $table = ViewBuilder::table();
        // 表格页标题
        $table->title = '附件列表';
        // 查询
        $table->query = function () use ($queryParams) {
            $query = Attachment::query();
            if (!empty($queryParams)) {
                $startAt = null;
                $endAt = null;
                if (isset($queryParams['created_at'])) {
                    list($startAt, $endAt) = DateSplitHelper::create($queryParams['created_at'])->reformat()->toArray();
                }

                $query->filterWhere([
                    'and',
                    // 分类名
                    ['type' => isset($queryParams['type']) ? $queryParams['type'] : null],
                    // 存储空间
                    ['bucket' => isset($queryParams['bucket']) ? $queryParams['bucket'] : null],
                    // 创建时间筛选
                    ['between', 'created_at', $startAt, $endAt],
                ]);
            }

            return $query;
        };
        $table->orderBy = [
            'id' => SORT_DESC,
        ];
        $table->columns = [
            'id' => table_column_helper('ID', ['style' => ['min-width' => '80']]),
            'type' => table_column_helper('分类名', ['style' => ['min-width' => '120px']]),
            'bucket' => table_column_helper('存储空间', ['style' => ['min-width' => '80px']]),
            'save_directory' => table_column_helper('目录', ['style' => ['min-width' => '130px']]),
            'path_prefix' => table_column_helper('路径前缀', []),
            'basename' => table_column_helper('文件名', ['style' => ['min-width' => '150px']]),
            'size' => table_column_helper('大小', ['style' => ['min-width' => '120px']], function ($item) {
                return filesize_unit_convert($item['size']);
            }),
            'ext' => table_column_helper('扩展名', ['style' => ['min-width' => '50px']]),
            'mime' => table_column_helper('文件类型', ['style' => ['min-width' => '150px']]),
            'hash' => table_column_helper('文件Hash值', ['style' => ['min-width' => '150px']]),
            'created_at' => table_column_helper('创建时间', ['style' => ['min-width' => '150px']]),
            'updated_at' => table_column_helper('更新时间', ['style' => ['min-width' => '150px']]),
        ];
        // 刷新
        $table->toolbarRefresh = [];
        // 筛选
        $table->toolbarFilter = [
            'columns' => [
                'type' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '分类名',
                    'placeholder' => '请选择分类名',
                    'options' => $typeMap,
                ]),
                'bucket' => table_toolbar_filter_helper([
                    'control' => ToolbarFilterOptions::CONTROL_SELECT,
                    'label' => '存储空间',
                    'placeholder' => '请选择存储空间',
                    'options' => $bucketMap,
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
            // 添加
            table_toolbar_custom_helper('left', [
                'title' => '添加未分类附件',
                'icon' => 'fa fa-plus',
                'option' => 'modal',
                'route' => 'admin/upload/add',
                'width' => '620px',
                'height' => '750px',
            ]),
            // 删除
            table_toolbar_custom_helper('left', [
                'title' => '删除未分类附件',
                'icon' => 'fa fa-remove',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/attachment/remove',
                'params' => ['id'],
            ]),
            // 复制到未分类
            table_toolbar_custom_helper('left', [
                'title' => '复制到未分类',
                'icon' => 'fa fa-copy',
                'option' => 'ajax',
                'method' => 'post',
                'route' => 'admin/attachment/copy',
                'params' => ['id', 'type'],
            ]),
        ];

        return $table->render($this);
    }

    /**
     * 获取指定分类简洁分页列表
     * @return string
     */
    public function actionList()
    {
        $get = empty_set_default($this->get, [
            'page' => 1,
            'limit' => 18,
            'save_directory' => 'common',
            'path_prefix' => 'default',
        ]);

        $offset = ($get['page'] - 1) * $get['limit'];
        $currentPage = (int)$get['page'];
        $query = Attachment::query([
            'id',
            'bucket', // 空间
            'save_directory', // 保存目录
            'path_prefix', // 路径前缀
            'basename', // 文件名
        ])->where(['save_directory' => $get['save_directory'], 'path_prefix' => $get['path_prefix']])
            ->offset($offset)
            ->limit($get['limit'])
            ->orderBy(['id' => SORT_DESC]);
        $count = $query->count();
        $all = $query->all();
        $data = [];
        foreach ($all as $val) {
            if ($val['bucket'] !== Uploads::IMAGE_STORAGE_BUCKET) {
                $url = into_full_url(Yii::$app->params['admin_default_file_image']);
            } else {
                $url = Yii::$app->uploads->getAttachmentUrl($val['bucket'], $val['save_directory'], $val['path_prefix'], $val['basename']);
            }
            
            array_push($data, [
                'id' => $val['id'],
                'url' => $url,
            ]);
        }

        $surplusCount = $count - ($currentPage * $get['limit']);
        return $this->asSuccess('请求成功', [
            'list' => $data,
            'currentPage' => $currentPage,
            'surplusCount' => $surplusCount > 0 ? $surplusCount : 0,
        ]);
    }

    /**
     * 删除附件
     * @return string
     */
    public function actionRemove()
    {
        $result = notset_return($this->post, [
           'id'
        ]);
        if ($result !== true) {
            return $this->asFail($result);
        }

        Attachment::deleteAll(['id' => $this->post['id']]);
        return $this->asSuccess('删除成功');
    }

    public function actionCopy()
    {

    }
}