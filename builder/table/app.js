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
            $scope.page = 1;
            $scope.perPage = 20;
            $scope.link = '<?= $link ?>';
            var param = {
                page: $scope.page,
                'per-page': $scope.perPage,
            };
            ($scope.getList = function () {
                $http.get($scope.link + '?' + $.param(param)).then(function (result) {
                    var data = result.data;
                    $scope.ymPage = data.page;
                    $scope.list = data.data;
                }, function (error) {
                    console.error(error);
                })
            }());

            // 获取分页列表数据
            $scope.getPage = function (link) {
                var i = $YmSpinner.show();

                $http.get(link).then(function (result) {
                    $YmSpinner.hide(i);
                    $scope.ymPage = result.data.page;
                    $scope.list = result.data.data;
                }, function (error) {
                    $YmSpinner.hide(i);
                    console.error(error)
                });
            };

            // 更改每页数据条数
            $scope.dumpPage = function (link) {
                var pageSize = $scope.pageSelect;
                $scope.getPage(link, pageSize);
            };
        }]);
}(window, window.angular);