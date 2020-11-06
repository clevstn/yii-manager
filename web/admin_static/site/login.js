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
        console.log("%c This is Login-Page with Yii-Manager", 'color:#337ab7;');
        // 初始化脚本
        var initedScript = function () {
            // 登录错误信息
            $scope.loginErr = '';
        };
        // 当[邮箱/用户名]失焦时,检查用户名是否正确
        $scope.checkUser = function () {
            if ($scope.usernameOrEmail) {
                var index = layer.load(2, {time: 10 * 1000});
                $http.post(YmApp.$adminApi.checkUser, {
                    usernameOrEmail: $scope.usernameOrEmail,
                }).then(function (result) {
                    layer.close(index);
                    var data = result.data;
                    if (data.code !== 200) {
                        layer.tips(data.msg, "#usernameOrEmail", {time: 1500, tips: 1});
                    }
                }, function (error) {
                    layer.close(index);
                    console.error(error);
                    toastr.error(error.data || "系统错误", "通知");
                });
            }
        };
        // 当[input]控件聚焦时,初始化页面
        $scope.initPage = function () {
            if ($scope.loginErr) {
                $scope.loginErr = "";
            }
        };
        // 登录提交
        $scope.loginSubmit = function () {
            if (!$scope.usernameOrEmail) {
                layer.tips('请输入邮箱/用户名', "#usernameOrEmail", {time: 1500, tips: 1});
                return;
            }

            if (!$scope.password) {
                layer.tips('请输入登录密码', "#password", {time: 1500, tips: 1});
                return;
            }

            // 登录提交
            var authLoading = YmSpinner.show('登录认证中,请稍后');
            $http.post(YmApp.$adminApi.loginSubmit, {
                usernameOrEmail: $scope.usernameOrEmail,
                password: $scope.password,
            }).then(function (result) {
                YmSpinner.hide(authLoading);
                var data = result.data;
                if (data.code === 200) {
                    $timeout(function () {
                        window.location.href = YmApp.$adminApi.loginSafePage;
                    });
                } else {
                    $scope.loginErr = data.msg;
                }
            }, function (error) {
                YmSpinner.hide(authLoading);
                console.error(error);
                toastr.error(error.data || "系统错误", "通知");
            });
        };
        // 其他登录方式, asm: app扫码
        $scope.otherLgn = function (code, $event) {
            switch (code) {
                case 'asm': // app扫码
                    layer.tips('APP扫码暂不支持', $event.currentTarget, {time: 1500, tips: 3});
                    break;
            }
        };

        // 初始化脚本
        initedScript();
    }]);
}(window, window._EasyApp);
