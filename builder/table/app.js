/**
 * table builder js
 * @author cleverstone
 * @since 1.0
 */
!function (window, angular) {
    "use strict";

    var $thisApp = angular.module("thisApp", ["$YmApp"]);
    $thisApp.controller('thisCtrl', [
        "$scope",
        "$http",
        "$timeout",
        "$interval",
        "$rootScope",
        "$YmApp",
        "$toastr",
        "$jq",
        "$yii",
        "$YmSpinner",
        "$swal",
        "$laydate",
        function (
            $scope,
            $http,
            $timeout,
            $interval,
            $rootScope,
            $YmApp,
            $toastr,
            $jq,
            $yii,
            $YmSpinner,
            $swal,
            $laydate
        ) {
            // 初始化数据列表
            ($scope.initList = function () {
                $scope.list = <?= $data ?>;
            }());


        }]);
}(window, window.angular);