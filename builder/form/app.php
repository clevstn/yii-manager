<?php
/* @var \yii\web\View $this 当前视图组件实例 */
/* @var array $_fields          表单字段集合 */

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
        var _easyApp = angular.module("EasyApp", ["YmAppModule", "ngFileUpload"]);
        _easyApp.controller('formCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "wangEditor", "Upload", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, wangEditor, Upload) {
            // 挂载WangEditor
            var mountedWangEditor = function () {
                if (typeof wangEditor !== "undefined") {
                    jQuery(function () {
                        jQuery(".YmWangEditor").each(function () {
                            var editor = new wangEditor(this);
                            editor.create();
                        });
                    });
                }
            };
            // 挂载日期插件
            var mountedLaydate = function () {
                jQuery(".ymFormDates").each(function () {
                    var id = jQuery(this).attr('id');
                    var range = jQuery(this).data('range');
                    var tag = jQuery(this).data('type');
                    var options = {
                        elem: "#" + id,
                        type: tag,
                        calendar: true,
                        done: function (value, date, endDate) {
                            /* 触发input事件 */
                            jQuery(this.elem).val(value).trigger("change");
                        }
                    };

                    if (range == 1) {
                        options.range = '/';
                    }

                    laydate.render(options);
                });
            };
            // 初始化表单
            var ymInitForm = function () {
                // 挂载WangEditor
                mountedWangEditor();
                // 挂载Laydate
                mountedLaydate();
                // 初始化表单默认值

            };
            // 返回上一页
            $scope.ymFormGoBack = function () {
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
            // 上传图片
            $scope.ymFormUploadImage = function (files, src) {
                Upload.base64DataUrl(files).then(function(urls){
                    $scope[src] = urls;
                });
            };
            // 重置表单
            $scope.ymFormResetForm = function () {

            };
            // 提交表单
            $scope.ymFormSubmitForm = function () {

            };

            // 初始化表单[调用]
            ymInitForm();

        }]);
    }(window, window.angular);
</script>
