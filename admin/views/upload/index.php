<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\UploadAsset;

/* @var $this \yii\web\View */
/* @var string $fields 上传请求参数 */
/* @var $param */

UploadAsset::register($this);

$this->title = '附件管理';
?>

<div class="panel panel-white pb-0" ng-controller="_EasyApp_AttachmentCtrl">
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
                <input class="upload-input" ngf-change="triggerUpload($files, <?= html_escape($fields) ?>)" ngf-select="true" ngf-multiple="true" type="file" title="附件上传">
            </div>
        </div>
    </div>
    <!--管理-->
    <div class="panel-body pt-0">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="btn-group pull-left">
                    <button type="button" class="btn btn-sm btn-default">选择未分类附件</button>
                </div>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default btn-sm" title="刷新">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" title="删除选中">删除选中</button>
                </div>
            </div>
            <div class="box-shadow bg-white" style="height: 360px; overflow-y: auto;">
                <div class="panel-body flex">
                    <div ng-repeat="(key, item) in [1,2,3,4,5,6,7,8,9,10] track by key">
                        <div class="image-wrap cp" ng-click="triggerChooseFile($event, item, key)">
                            <img class="image-ui" src="/media/image/admin_static/default-0.jpg" alt>
                            <span class="success-icon"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-xs-6 clearfix">
                        <button type="button" title="上一页" class="layui-btn layui-btn-sm btn-default pull-left">
                            <i class="layui-icon layui-icon-left text-primary"></i>
                        </button>
                    </div>
                    <div class="col-xs-6 clearfix">
                        <button type="button" title="下一页" class="layui-btn layui-btn-sm btn-default pull-right">
                            <i class="layui-icon layui-icon-right text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
