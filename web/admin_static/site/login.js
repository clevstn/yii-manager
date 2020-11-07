/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
!function (window, _EasyApp) {
    "use strict";

    // 登录 - 基本校验
    _EasyApp.controller('_loginBaseCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        console.log("%c This is Login-Base-Page with Yii-Manager", 'color:#337ab7;');
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
        // 登录提交
        $scope.loginSubmit = function () {
            // 点击登录按钮初始化页面错误信息
            if ($scope.loginErr) {
                $scope.loginErr = "";
            }

            // 检查邮箱/用户名是否填写
            if (!$scope.usernameOrEmail) {
                layer.tips('请输入邮箱/用户名', "#usernameOrEmail", {time: 1500, tips: 1});
                return;
            }

            // 检查登陆密码是否填写
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
                    // 登录校验成功进行二次校验或直接进入首页
                    $timeout(function () {
                        window.location.href = data.data;
                    });
                } else {
                    // 登录校验错误,显示错误信息
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
    }]).controller('_loginSafeCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        // 登录 - 安全校验
        console.log("%c This is Login-Safe-Page with Yii-Manager", 'color:#337ab7;');

    }]);
}(window, window._EasyApp);
