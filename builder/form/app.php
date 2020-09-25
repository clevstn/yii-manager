<?php

// 注意这里必须是<script>...</script>的形式
?>
<script>
    /**
     * form builder script
     * @author cleverstone
     * @since 1.0
     */
    !function (window, angular) {
        "use strict";
        var _easyApp = angular.module("EasyApp", ["YmAppModule"]);
        _easyApp.controller('formCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "wangEditor", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, wangEditor) {
            // 初始化[定义]
            $scope.initForm = function () {
                // 挂载WangEditor
                $scope.mountedWangEditor();

                //
            };

            // 挂载WangEditor
            $scope.mountedWangEditor = function () {
                if (typeof wangEditor !== "undefined") {
                    jQuery(function () {
                        jQuery(".YmWangEditor").each(function () {
                            var editor = new wangEditor(this);
                            editor.create();
                        });
                    });
                }
            };

            // 返回上一页
            $scope.goBack = function () {
                var referrer = window.document.referrer;
                if (window.self !== window.top) {
                    // 在iframe中
                    window.self.history.back();
                } else if (referrer) {
                    // 不在iframe中,如果存在来源则返回来源并刷新页面
                    window.self.location.href = referrer;
                } else {
                    // 不存在来源则使用history
                    window.self.history.go(-1);
                }
            };

            // 重置表单
            $scope.resetForm = function () {

            };

            // 提交表单
            $scope.submitForm = function () {

            };

            // 初始化表单[调用]
            $scope.initForm();

        }]);
    }(window, window.angular);
</script>
