<?php
/* @var \yii\web\View $this 当前视图组件实例 */
/* @var array $_fields      表单字段集合 */

use app\builder\form\FieldsOptions;
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
            var wangEditorMap = {};
            var mountedWangEditor = function () {
                if (typeof wangEditor !== "undefined") {
                    jQuery(".YmFormWangEditor").each(function () {
                        var id = jQuery(this).attr('id');
                        var editor = new wangEditor(this);
                        editor.create();
                        wangEditorMap[id] = editor;
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
            // 初始化表单默认值
            var initFormValues = function () {
                var scopeFields = {};
                <?php foreach ($_fields as $field => $options): ?>
                <?php switch ($options['control']): case FieldsOptions::CONTROL_TEXT: //文本 ?>
                <?php case FieldsOptions::CONTROL_NUMBER: // 数字 ?>
                <?php case FieldsOptions::CONTROL_PASSWORD: // 密码 ?>
                <?php case FieldsOptions::CONTROL_DATETIME: // 日期，格式：Y-m-d H:i:s ?>
                <?php case FieldsOptions::CONTROL_DATE: // 日期，格式：Y-m-d ?>
                <?php case FieldsOptions::CONTROL_YEAR: // 年，格式：Y ?>
                <?php case FieldsOptions::CONTROL_MONTH: // 月，格式：m ?>
                <?php case FieldsOptions::CONTROL_TIME: // 时，格式：H:i:s ?>
                <?php case FieldsOptions::CONTROL_SELECT: // 下拉选择 ?>
                <?php case FieldsOptions::CONTROL_HIDDEN: // 隐藏 ?>
                <?php case FieldsOptions::CONTROL_TEXTAREA: // 文本域 ?>
                scopeFields['<?= $field ?>'] = '<?= $options['default'] ?>';
                <?php break; case FieldsOptions::CONTROL_FILE: // 文件 ?>
                var fileDefaults = '<?= $options['default'] ?>';
                fileDefaults = fileDefaults.split(',');
                var fileNumbers = <?= $options['number'] ?>;
                for (var i = 0; i < fileNumbers; i++) {
                    if (fileDefaults[i] === "" || fileDefaults[i] === void 0) {
                        fileDefaults[i] = "0";
                    }
                }
                scopeFields['<?= $field ?>'] = fileDefaults.join(',');
                <?php break; case FieldsOptions::CONTROL_CHECKBOX: // 多选 ?>
                var defaultValues = "<?= $options['default'] ?>";
                defaultValues = defaultValues.split(',');
                jQuery(".ymFormCheckbox_<?= $field ?>").each(function () {
                    var currentValue = jQuery(this).val();
                    if (defaultValues.indexOf(currentValue) !== -1) {
                        jQuery(this).iCheck("check");
                    }
                });
                <?php break; case FieldsOptions::CONTROL_RADIO: // 单选 ?>
                jQuery(".ymFormRadio_<?= $field ?>").each(function () {
                    var currentValue = jQuery(this).val();
                    if (currentValue == "<?= $options['default'] ?>") {
                        jQuery(this).iCheck("check");
                    }
                });
                <?php break; case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
                var thisRichtxtId = "ymFormRichtext_<?= $field ?>";
                var thisEditor = wangEditorMap[thisRichtxtId];
                if (thisEditor) {
                    thisEditor.txt.html("<?= $options['default'] ?>");
                }
                <?php break; case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
                <?php default: // 自定义 ?>

                <?php endswitch; ?>
                <?php endforeach; ?>

                $scope.ymFormFields = scopeFields;
            };
            // 初始化表单
            var ymInitForm = function () {
                // 挂载WangEditor
                mountedWangEditor();
                // 挂载Laydate
                mountedLaydate();
                // 初始化表单默认值
                initFormValues();
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
            $scope.ymFormUploadImage = function (files, src, field, index) {
                if (files) {
                    var attachIds = $scope.ymFormFields[field];
                    attachIds = attachIds.split(',');
                    console.log('olds:' + attachIds);
                    // 预览文件
                    Upload.base64DataUrl(files).then(function(urls){
                        $scope[src] = urls;
                    });

                    // 上传文件
                    // 上传成功重新赋值
                    attachIds[index] = 520;
                    attachIds = attachIds.join(',');
                    console.log('news:' + attachIds);
                    $scope.ymFormFields[field] = attachIds;
                }
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
