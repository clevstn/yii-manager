/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * 后台首页
 * @author cleverstone
 * @since ym1.0
 */
!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_EasyApp_CountCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        /*******************概览********************/


    }]).controller('_EasyApp_QuickOpCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        /*******************快捷操作********************/
        // 初始化数据
        $scope._initData = function () {
            // 快捷菜单列表
            $scope.ymQuickActionList = [];
        };

        // 点击设置
        $scope.quickActionsShow = function () {
            var layerParams = YmApp.layerParseParams('420px');
            window.layer.open({
                type: 2,
                shade: 0.3,
                anim: -1,
                title: '设置快捷操作',
                maxmin: false,
                shadeClose: false,
                btn: ['关闭'],
                closeBtn: layerParams.closeBtn,
                area: [layerParams.width, '650px'],
                content: YmApp.$adminApi.indexQuickActionUrl,
            });
        };

        // 获取快捷菜单列表
        $scope._initList = function () {
            // 节流
            $scope.quickLoadingShow = true;
            $http.get(YmApp.$adminApi.indexQuickActionListUrl).then(function (result) {
                $scope.quickLoadingShow = false;
                var data = result.data;

                $scope.ymQuickActionList = data.data || [];
            }, function (error) {
                $scope.quickLoadingShow = false;
                toastr.error(error.data || "数据加载失败，请稍后重试", "通知");
                window.console.error(error);
            });
        };

        // 点击快捷菜单
        $scope.hrefTarget = function (url, label) {
            var layerParams = YmApp.layerParseParams('80%');
            layer.open({
                type: 2,
                shade: 0.3,
                anim: -1,
                title: label,
                maxmin: true,
                shadeClose: false,
                btn: ['关闭'],
                closeBtn: 1,
                area: [layerParams.width, '90%'],
                content: url
            });
        };

        // 初始化控制器
        $scope._initData();
        $scope._initList();

        // 置顶重载列表方法
        window._EasyApp_QuickActionRefresh = window._EasyApp_QuickActionRefresh || {};
        window._EasyApp_QuickActionRefresh._initQuickActionList = function () {
            $scope._initList();
        };

    }]).controller('_EsayApp_MessageCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        /*******************消息********************/

    }]);
}(window, window._EasyApp);