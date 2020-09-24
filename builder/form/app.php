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
        var $thisApp = angular.module("thisApp", ["$YmApp"]);
        $thisApp.controller('thisCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "$YmApp", "$toastr", "$jq", "$yii", "$YmSpinner", "$swal", "$laydate", "$wangEditor", function ($scope, $http, $timeout, $interval, $rootScope, $YmApp, $toastr, $jq, $yii, $YmSpinner, $swal, $laydate, $wangEditor) {
            // 初始化
            $scope.initForm = function () {
                // 初始化WangEditor
                if (typeof $wangEditor !== "undefined") {
                    $jq(function () {
                        $jq(".YmWangEditor").each(function () {
                            var editor = new $wangEditor(this);
                            editor.create();
                        });
                    });
                }


            };
            $scope.initForm();


        }]);
    }(window, window.angular);
</script>
