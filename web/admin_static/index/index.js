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
    _EasyApp.controller('_countCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        // 概览

    }]).controller('_quickOpCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        // 快捷操作

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

    }]).controller('_messageCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        // 消息

    }]);
}(window, window._EasyApp);