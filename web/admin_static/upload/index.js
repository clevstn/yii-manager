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
        var uploadQueryParam = window._EasyApp_UploadQueryParams;

        $scope.initData = function () {
            $scope.currentPage = 1;
            $scope.surplusCount = 0;
            $scope.data = [];

            $scope.chooseAttachments = [];

            $scope.previewFiles = [];
            $scope.uploadProgressValue = 0;
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

        // 选择未分类
        $scope.selectDefaultAttachment = function () {
            if (!uploadQueryParam.save_directory || uploadQueryParam.save_directory === 'common') {
                YmApp._layerTip('无法选择未分类附件组', '提示', 0);
                return;
            }

            var currentType = uploadQueryParam.type;
            var currentSaveDirectory = uploadQueryParam.save_directory;
            var currentPathPrefix = uploadQueryParam.path_prefix || 'default';

            window.layer.open({
                type: 2,
                shade: 0.3,
                anim: -1,
                title: '未分类附件',
                maxmin: false,
                shadeClose: false,
                btn: ['加入当前分类', '关闭'],
                closeBtn: 1,
                area: ['100%', '90%'],
                content: YmApp.addUrlQueryParam(YmApp.$adminApi.attachmentListUrl, {
                    save_directory: 'common',
                    path_prefix: 'default'
                }),
                yes: function (index, layero) {
                    var win = window[layero.find('iframe')[0].name];
                    var choose = win._EasyApp_UploadUndefinedChooseAttachments();
                    if (choose.length <= 0) {
                        YmApp._layerTip('请您选择一张图片', '提示', 0);
                        return;
                    }

                    var ids = [];
                    choose.forEach(function (item) {
                        ids.push(item.id);
                    });

                    $http.post(YmApp.$adminApi.copyAttachmentUrl, {
                        'id': ids,
                        'type': currentType,
                        'save_directory': currentSaveDirectory,
                        'path_prefix': currentPathPrefix,
                    }).then(function (result) {
                        layer.close(index);
                        var data = result.data;
                        if (data.code !== 200) {
                            YmApp._layerTip(data.msg, '警告', 0);
                        } else {
                            // 刷新列表
                            $scope.getList();
                        }
                    }, function (error) {
                        layer.close(index);
                        YmApp._layerTip(error.toString(), '错误', 2);
                        window.console.error(error);
                    });



                },
                btn2: function (index) {
                    layer.close(index);
                }
            });

        };

        // 删除选中
        $scope.removeSelected = function () {
            if ($scope.chooseAttachments.length <= 0) {
                YmApp._layerTip('您还没选择附件呢', '提示', 0);
                return;
            }

            var fields = {};
            fields[yii.getCsrfParam()] = yii.getCsrfToken();
            fields.id = [];
            $scope.chooseAttachments.forEach(function (item, index) {
                fields.id[index] = item.id;
            });

            layer.confirm('确认删除么？删除后不可恢复！', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                layer.close(index);
                $http.post(YmApp.$adminApi.removeAttachmentUrl, fields).then(function (result) {
                    var data = result.data;
                    if (data.code === 200) {
                        // 刷新列表
                        $scope.getList();
                    } else {
                        YmApp._layerTip(data.msg, '警告', 5);
                    }
                }, function (error) {
                    window.console.log(error);
                    YmApp._layerTip(error.toString(), '错误', 2);
                });
            }, function (index) {
                layer.close(index);
            });
        };

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
                save_directory: uploadQueryParam.save_directory || '',
                path_prefix: uploadQueryParam.path_prefix || ''
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

        // 文件上传
        // 后端post大小最大是8M
        $scope.triggerUpload = function (files) {
            var fields = YmApp.deepCopy(uploadQueryParam);
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
                    if (data.code === 200) {
                        YmApp._layerTip(data.msg, '提示', 1);
                        $scope.getList(1);
                    } else {
                        YmApp._layerTip(data.msg, '错误', 2);
                    }
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

        // 暴露给父窗口
        window._EasyApp_UploadChooseAttachments = function () {
            return $scope.chooseAttachments;
        };
        // 初始化列表
        $scope.getList();
    }]);
}(window, window._EasyApp);