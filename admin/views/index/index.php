<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\admin\assets\IndexAsset;

/* @var $this \yii\web\View */
/* @var $param */
IndexAsset::register($this);
$this->title = t('home', 'app.admin');
?>
<!--概览-->
<div class="panel panel-default" ng-controller="_countCtrl">
    <div class="panel-heading border-bottom">
        <span class="f-14">概览</span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <p class="text-center f-15"><b>用户</b></p>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-white">
                    <div class="panel-heading text-center">
                        <p class="text-center f-15"><b>订单</b></p>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-white">
                    <div class="panel-heading text-center">
                        <p class="text-center f-15"><b>资金</b></p>
                    </div>
                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------------------------------------------------->
<div class="row">
    <!--快捷操作-->
    <div class="col-md-6">
        <div class="panel panel-default" ng-controller="_quickOpCtrl">
            <div class="panel-heading border-bottom clearfix">
                <span class="f-14">快捷操作</span>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-primary pull-right" ng-click="quickActionsShow()">
                    <i class="glyphicon glyphicon-cog"></i>
                    点击设置
                </button>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="panel panel-white">
                            <div class="panel-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------------------------->
    <!--消息-->
    <div class="col-md-6">
        <div class="panel panel-default" ng-controller="_messageCtrl">
            <div class="panel-heading border-bottom">
                <span class="f-14">消息</span>
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>
</div>
