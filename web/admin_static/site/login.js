/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_loginCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        console.log("%c This is Login-Page with Yii-Manager", 'color:blue;');
        // 登录提交
        $scope.loginSubmit = function () {

        };
        // 其他登录方式, asm: app扫码
        $scope.otherLgn = function (code, $event) {
            switch (code) {
                case 'asm': // app扫码
                    layer.tips('APP扫码暂不支持', $event.currentTarget, {time: 1500, tips: 3});
                    break;
            }
        };
    }]);
}(window, window._EasyApp);
