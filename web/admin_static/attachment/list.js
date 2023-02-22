/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * 附件分类列表
 * @author cleverstone
 * @since ym1.0
 */

!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_EasyApp_AttachmentListCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
        // 初始化数据
        var queryParam = window._EasyApp_AttachListQueryParams;
        // 初始化数据
        $scope.initData = function () {
            $scope.currentPage = 1;
            $scope.surplusCount = 0;
            $scope.data = [];

            $scope.chooseAttachments = [];
        };

        // 初始化数据
        $scope.initData();

        // 初始化附件选中状态
        function initImgSelectStatus() {
            $scope.chooseAttachments = [];
            jQuery('.ymImgSelect').each(function () {
                if (jQuery(this).hasClass('selected')) {
                    jQuery(this).removeClass('selected');
                }
            });
        }

        // 上一页
        $scope.prevPage = function () {
            var prev = $scope.currentPage - 1;
            if (prev <= 0) {
                return;
            }

            $scope.getList(prev);
        };

        // 获取当前分类列表
        $scope.getList = function (page, limit) {
            page = page || $scope.currentPage;
            limit = limit || 18;

            var url = YmApp.addUrlQueryParam(YmApp.$adminApi.attachmentListUrl, {
                page: page,
                limit: limit,
                save_directory: queryParam.save_directory || '',
                path_prefix: queryParam.path_prefix || ''
            });

            // 节流
            $scope.attachmentListLoadingShow = true;
            $http.get(url).then(function (result) {
                $scope.attachmentListLoadingShow = false;
                initImgSelectStatus();

                var data = result.data.data;
                $scope.data = data.list || [];

                $scope.currentPage = data.currentPage;
                $scope.surplusCount = data.surplusCount;

            }, function (error) {
                $scope.attachmentListLoadingShow = false;
                toastr.error(error.data || "数据加载失败，请稍后重试", "通知");
                window.console.error(error);
            });
        };

        // 下一页
        $scope.nextPage = function () {
            if ($scope.surplusCount <= 0) {
                return;
            }

            $scope.getList($scope.currentPage + 1);
        };

        // 点击选中、取消选中文件
        $scope.triggerChooseFile = function ($event, id, path, url) {
            var othis = jQuery($event.currentTarget);

            if (othis.hasClass('selected')) {
                othis.removeClass('selected');
                $scope.chooseAttachments.forEach(function (item, key) {
                    if (item.id === id) {
                        $scope.chooseAttachments.splice(key, 1);
                    }
                });
            } else {
                othis.addClass('selected');
                $scope.chooseAttachments.push({
                    id: id,
                    path: path,
                    url: url
                });
            }
        };

        // 暴露给父窗口
        window._EasyApp_UploadUndefinedChooseAttachments = function () {
            return $scope.chooseAttachments;
        };

        // 初始化列表
        $scope.getList();
    }]);
}(window, window._EasyApp);