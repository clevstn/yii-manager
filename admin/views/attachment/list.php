<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\AttachmentAsset;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var $param */
/* @var array $fields 请求参数 */

AttachmentAsset::register($this);

$this->title = '列表';
?>
<script>
    window._EasyApp_AttachListQueryParams = <?= Json::encode($fields) ?>;
</script>
<div class="panel panel-default" ng-controller="_EasyApp_AttachmentListCtrl">
    <div class="panel-body">
        <div class="box-shadow bg-white" style="min-height: 320px; overflow-y: auto;">
            <block-loading display="attachmentListLoadingShow"></block-loading>
            <div class="panel-body flex" ng-hide="attachmentListLoadingShow">
                <div ng-repeat="(key, item) in data track by key">
                    <div title="{{item.origin_name}}" class="ymImgSelect image-wrap cp" ng-click="triggerChooseFile($event, item.id, item.path, item.url)">
                        <img class="image-ui" ng-src="{{ item.url }}" alt>
                        <span class="success-icon"></span>
                    </div>
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
