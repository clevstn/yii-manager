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
        };

        // 文件上传
        $scope.triggerUpload = function (files, fields) {
            fields[yii.getCsrfParam()] = yii.getCsrfToken();

            Upload.upload({
                url: YmApp.$adminApi.fileUploadUrl,
                fields: fields,
                file: files
            }).progress(function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log(progressPercentage);
            }).success(function (data, status, headers, config) {
                console.log(data);
            }).error(function (data, status, headers, config) {
                console.log('error status: ' + status);
            })
        };


        // 点击选中、取消选中文件
        $scope.triggerChooseFile = function ($event, id) {
            var othis = jQuery($event.currentTarget);

            if (othis.hasClass('selected')) {
                othis.removeClass('selected');
                $scope.chooseAttachments.splice($scope.chooseAttachments.indexOf(id), 1)
                console.log($scope.chooseAttachments)
            } else {
                othis.addClass('selected');
                $scope.chooseAttachments.push(id);
                console.log($scope.chooseAttachments)
            }
        };

        // 初始化数据
        $scope.initData();
        // 传址并暴露
        window._EasyApp_UploadChooseAttachments = $scope.chooseAttachments;
    }]);
}(window, window._EasyApp);