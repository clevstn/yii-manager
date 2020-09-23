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
        $thisApp.controller('thisCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "$YmApp", "$toastr", "$jq", "$yii", "$YmSpinner", "$swal", "$laydate", function ($scope, $http, $timeout, $interval, $rootScope, $YmApp, $toastr, $jq, $yii, $YmSpinner, $swal, $laydate) {
            // ...

        }]);
    }(window, window.angular);
</script>
