/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * MFA绑定
 * @author cleverstone
 * @since ym1.0
 */

!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_EasyApp_otpBindCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
        // 查看账号和密匙
        $scope.viewUserSecret = function () {
            layer.open({
                type: 1,
                closeBtn: 1,
                btn: '关闭',
                content: jQuery('#_viewUserAndSecret')
            });
        };
    }]);
}(window, window._EasyApp);