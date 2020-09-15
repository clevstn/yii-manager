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
            // 获取URL
            var link = '<?= $link ?>';
            $scope.getUrl = function (page, perPage) {
                page = page || 1;
                perPage = perPage || 20;
                var param = {
                    page: page,
                    'per-page': perPage,
                };

                return link + '?' + $jq.param(param);
            };
            // 获取数据列表
            $scope.getList = function (page, perPage) {
                var i = $YmSpinner.show();
                $http.get($scope.getUrl(page, perPage)).then(function (result) {
                    $YmSpinner.hide(i);
                    var data = result.data;
                    $scope.ymPage = data.page;
                    $scope.list = data.data;
                }, function (error) {
                    $YmSpinner.hide(i);
                    $toastr.error('错误', '数据列表加载失败');
                    console.error(error);
                });
            };
            // 初始化
            ($scope.init = function () {
                $scope.getList();
            }());
            // 分页
            $scope.getPage = function (page, perPage) {
                $scope.getList(page, perPage);
            };
            // 设置数据条数
            $jq('body').on('change', '#pageSelect', function () {
                $scope.getList(1, $jq(this).val());
            });

        }]);
}(window, window.angular);