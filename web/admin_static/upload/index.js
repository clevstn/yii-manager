/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * 附件管理
 * @author cleverstone
 * @since ym1.0
 */

!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_EasyApp_AttachmentCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "Upload", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, Upload, layui) {
        // 初始化数据
        $scope.initData = function () {
            $scope.chooseAttachments = [];

            $scope.previewFiles = [];
            $scope.uploadProgressValue = 0;
        };

        // 文件上传
        // 后端post大小最大是8M
        $scope.triggerUpload = function (files, fields) {
            if (YmApp.typeOf(files) === 'array' && files.length) {
                var size = 0;
                files.forEach(function (item) {
                    size = YmApp.numberAdd(item.size, size);
                });

                size = YmApp.numberDiv(size, 1024 * 1024);
                if (size >= 8) {
                    YmApp._layerTip('所有文件大小总和不能大于8M!', '警告', 5);
                    return;
                }

                fields[yii.getCsrfParam()] = yii.getCsrfToken();
                $scope.previewFiles = files;
                $scope.uploadProgressValue = 0;

                Upload.upload({
                    url: YmApp.$adminApi.fileUploadUrl,
                    fields: fields,
                    file: files
                }).progress(function (evt) {
                    var loadProgress = parseInt(100 * evt.loaded / evt.total);
                    if (loadProgress >= 100) {
                        $scope.uploadProgressValue = 99;
                    } else {
                        $scope.uploadProgressValue = loadProgress;
                    }

                }).success(function (data, status, headers, config) {
                    $scope.previewFiles = [];
                    $scope.uploadProgressValue = 100;

                    YmApp._layerTip('附件上传成功!');

                }).error(function (data, status, headers, config) {
                    $scope.previewFiles = [];
                    $scope.uploadProgressValue = 100;

                    YmApp._layerTip('服务器请求失败，无法上传！', '错误', 2);
                    window.console.log('error status: ' + status);
                })
            }
        };


        // 点击选中、取消选中文件
        $scope.triggerChooseFile = function ($event, id, url) {
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
                    url: url
                });
            }
        };

        // 初始化数据
        $scope.initData();
        // 传址并暴露
        window._EasyApp_UploadChooseAttachments = $scope.chooseAttachments;
    }]);
}(window, window._EasyApp);