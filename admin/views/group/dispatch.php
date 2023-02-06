<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\PermissionDispatchAsset;

/* @var $this \yii\web\View */
/* @var $param */
PermissionDispatchAsset::register($this);
$this->title = '管理组权限分配';
?>
<div class="panel panel-default" ng-controller="dispatchCtrl">
    <div class="panel-body">
        <div class="form-group col-md-12">
            <div id="PermissionNodeTree"></div>
        </div>
    </div>
    <div class="panel-footer clearfix">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-sm btn-default" lay-tree="close">关闭</button>
            <button type="button" class="btn btn-sm btn-default" lay-tree="reload">重置</button>
            <button type="button" class="btn btn-sm btn-primary" lay-tree="getChecked">提交</button>
        </div>
    </div>
</div>
<script>
    window.YmData = {
        dispatchPermissionData : <?= $data ?>
    };
</script>