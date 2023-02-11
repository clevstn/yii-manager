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
<div class="panel panel-default mb-15" ng-controller="_EasyApp_CountCtrl">
    <div class="panel-heading border-bottom">
        <span class="f-14">概览</span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-white">
                    <div class="panel-heading text-center">
                        <p class="text-center f-15"><b>订单统计</b></p>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h5>【完成单】</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>订单总数：</label>
                                            <p class="f-15">
                                                <span>1万</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>1000人</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h5>【取消单】</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>订单总数：</label>
                                            <p class="f-15">
                                                <span>1万</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>1000单</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h5>【待支付单】</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>订单总数：</label>
                                            <p class="f-15">
                                                <span>10万单</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>1000单</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-white">
                    <div class="panel-heading text-center">
                        <p class="text-center f-15"><b>资金统计</b></p>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>冻结总额：</label>
                                            <p class="f-15">
                                                <span>1000万元</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>10万元</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>退款总额：</label>
                                            <p class="f-15">
                                                <span>1000万元</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>10万元</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>转支付总额：</label>
                                            <p class="f-15">
                                                <span>1000万元</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>10万元</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <p class="text-center f-15"><b>用户统计</b></p>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>用户总量：</label>
                                            <p class="f-15">
                                                <span>100万人</span>
                                            </p>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#">
                                            <label>今日新增：</label>
                                            <p class="f-15">
                                                <span>1000人</span>
                                                <i class="glyphicon glyphicon-arrow-up text-danger"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------------------------------------------------->
<div class="row pt-0">
    <!--快捷操作-->
    <div class="col-md-4 pr-0" ng-controller="_EasyApp_QuickOpCtrl">
        <div class="panel panel-default">
            <div class="panel-heading border-bottom clearfix">
                <span class="f-14">快捷操作</span>
                <button type="button" class="layui-btn layui-btn-sm layui-btn-primary pull-right" ng-click="quickActionsShow()">
                    <i class="glyphicon glyphicon-cog"></i>
                    点击设置
                </button>
            </div>
            <div class="panel-body" style="min-height:380px;">
                <block-loading ui-class="block-loading-quick-action" display="quickLoadingShow"></block-loading>
                <div ng-hide="quickLoadingShow">
                    <div class="col-md-3 col-md-130px pl-0 pr-10" ng-repeat="xxx in ymQuickActionList track by xxx.id">
                        <div class="panel panel-white hover-light cp" ng-click="hrefTarget(xxx.url, xxx.label)">
                            <div class="panel-body px-0">
                                <p class="text-center f-32 text-primary">
                                    <i class="{{ xxx.icon }}"></i>
                                </p>
                                <div class="caption">
                                    <p class="f-14 text-center" ng-bind="xxx.label | toHtml"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------------------------->
    <!--订单消息-->
    <div class="col-md-8" ng-controller="_EsayApp_MessageCtrl">
        <div class="panel panel-default">
            <div class="panel-heading border-bottom">
                <span class="f-14">订单消息</span>
            </div>
            <div class="panel-body" style="min-height:380px;">
                <div class="col-md-4 px-0">
                    <div class="list-group mb-0">
                        <h5 class="list-group-item text-center f-14">待发货</h5>
                        <div class="list-group-item">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td>订单号</td>
                                    <td>金额</td>
                                    <td>时间</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 px-0">
                    <div class="list-group mb-0">
                        <h5 class="list-group-item border-x-none text-center f-14">待完成</h5>
                        <div class="list-group-item border-x-none">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td>订单号</td>
                                    <td>金额</td>
                                    <td>时间</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                <tr>
                                    <td>20230211141101123456</td>
                                    <td>1000</td>
                                    <td>2023-02-11 14:12</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 px-0">
                    <div class="list-group mb-0">
                        <h5 class="list-group-item text-center f-14">待退款</h5>
                        <div class="list-group-item">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <td>订单号</td>
                                        <td>金额</td>
                                        <td>时间</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>20230211141101123456</td>
                                        <td>1000</td>
                                        <td>2023-02-11 14:12</td>
                                    </tr>
                                    <tr>
                                        <td>20230211141101123456</td>
                                        <td>1000</td>
                                        <td>2023-02-11 14:12</td>
                                    </tr>
                                    <tr>
                                        <td>20230211141101123456</td>
                                        <td>1000</td>
                                        <td>2023-02-11 14:12</td>
                                    </tr>
                                    <tr>
                                        <td>20230211141101123456</td>
                                        <td>1000</td>
                                        <td>2023-02-11 14:12</td>
                                    </tr>
                                    <tr>
                                        <td>20230211141101123456</td>
                                        <td>1000</td>
                                        <td>2023-02-11 14:12</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
