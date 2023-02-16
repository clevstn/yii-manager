<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\UploadAsset;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var array $fields 请求参数 */
/* @var $param */

UploadAsset::register($this);

$this->title = '附件管理';
?>
<script>
    window._EasyApp_UploadQueryParams = <?= Json::encode($fields) ?>;
</script>
<div class="panel panel-white pb-0 upload-container" ng-controller="_EasyApp_AttachmentCtrl">
    <!--上传-->
    <div class="panel-body">
        <!--上传区域-->
        <div class="upload-wap border-dashed-ddd h-130px text-center text-light cp">
            <div class="loading-preview" ng-show="previewFiles.length">
                <div class="preview-wrap" ng-repeat="(key, item) in previewFiles track by key">
                    <img ngf-src="item" alt>
                    <span class="loading-progress text-info" ng-bind="uploadProgressValue + '%'"></span>
                </div>
            </div>
            <div ng-hide="previewFiles.length">
                <p class="f-16 pt-16">点击上传或拖放图片到该区域</p>
                <i class="fa fa-upload f-48 pt-6"></i>
                <input class="upload-input" ngf-change="triggerUpload($files)" ngf-select="true" ngf-multiple="true" type="file" title="附件上传">
            </div>
        </div>
    </div>
    <!--管理-->
    <div class="panel-body pt-0">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">

                <div class="btn-group pull-left">
                    <?php if (isset($fields['save_directory']) && $fields['save_directory'] != 'common'): ?>
                    <button type="button" class="btn btn-sm btn-default" ng-click="selectDefaultAttachment()">选择未分类附件</button>
                    <?php endif; ?>
                </div>

                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default btn-sm" title="刷新" ng-click="getList(1)">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <?php if (vv('admin/attachment/remove') !== false): ?>
                    <button type="button" class="btn btn-sm btn-danger" title="删除选中" ng-click="removeSelected()">删除选中</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-heading">
                <span class="text-info">提示：如需查看原图片，请右键图片选择【在新标签页中打开图片】</span>
            </div>
            <div class="box-shadow bg-white" style="min-height: 320px; overflow-y: auto;">
                <block-loading display="attachmentListLoadingShow"></block-loading>
                <div class="panel-body flex" ng-hide="attachmentListLoadingShow">
                    <div ng-repeat="(key, item) in data track by key">
                        <div title="{{item.origin_name}}" class="ymImgSelect image-wrap cp" ng-click="triggerChooseFile($event, item.id, item.url)">
                            <img class="image-ui" ng-src="{{ item.url }}" alt>
                            <span class="success-icon"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-xs-6 clearfix">
                        <button type="button" title="上一页" class="layui-btn layui-btn-sm btn-default pull-left cp" ng-click="prevPage()">
                            <i class="layui-icon layui-icon-left text-primary"></i>
                        </button>
                    </div>
                    <div class="col-xs-6 clearfix">
                        <button type="button" title="下一页" class="layui-btn layui-btn-sm btn-default pull-right cp" ng-click="nextPage()">
                            <i class="layui-icon layui-icon-right text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
