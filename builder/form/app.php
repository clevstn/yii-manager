<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

use yii\helpers\Url;
use app\builder\form\FieldsOptions;

/* @var \yii\web\View $this 当前视图组件实例 */
/* @var array $_fields      表单字段集合 */
/* @var boolean $_autoBack  提交完成后是否自动返回 */
/* @var array $_innerScript  插入表单脚本内部的Js脚本 */

// 注意这里必须是<script>...</script>的形式
?>
<script>
    /**
     * form builder script
     * @author hili
     * @since 1.0
     */
    !function (window, _EasyApp) {
        "use strict";
        _EasyApp.controller('_formCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "wangEditor", "Upload", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, wangEditor, Upload) {
            // 封装Layer插件
            var parentLayer = window.parent.layer;
            var tips = function (msg, title, icon, callback) {
                msg = msg || '';
                title = title || '通知';
                icon = icon || 1;
                callback = callback || (new Function())
                parentLayer.alert(msg, {
                    closeBtn: 0,
                    title: title,
                    icon: icon,
                }, function (index) {
                    parentLayer.close(index);
                    callback();
                });
            };
            // 挂载富文本插件
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
            // 初始化表单
            var initFormValues = function () {
                var scopeFields = {};
                var fileDefaults;
                var fileNumbers;
                var checkboxDefaultValues;
                var thisRichtxtId;
                var thisEditor;
                var customInitFunc;
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
                fileDefaults = '<?= $options['default'] ?>';
                fileDefaults = fileDefaults.split(',');
                fileNumbers = <?= $options['number'] ?>;
                for (var i = 0; i < fileNumbers; i++) {
                    if (fileDefaults[i] === "" || fileDefaults[i] === void 0) {
                        fileDefaults[i] = "0";
                    }

                    // 初始化预览图
                    $scope['ymFormFileLink<?= $field ?>' + i] = "";
                }
                scopeFields['<?= $field ?>'] = fileDefaults.join(',');
                <?php break; case FieldsOptions::CONTROL_CHECKBOX: // 多选 ?>
                checkboxDefaultValues = "<?= $options['default'] ?>";
                checkboxDefaultValues = checkboxDefaultValues.split(',');
                jQuery(".ymFormCheckbox_<?= $field ?>").each(function () {
                    var currentValue = jQuery(this).val();
                    if (checkboxDefaultValues.indexOf(currentValue) !== -1) {
                        jQuery(this).iCheck("check");
                    } else {
                        jQuery(this).iCheck("uncheck");
                    }
                });
                <?php break; case FieldsOptions::CONTROL_RADIO: // 单选 ?>
                jQuery(".ymFormRadio_<?= $field ?>").each(function () {
                    var currentValue = jQuery(this).val();
                    if (currentValue == "<?= $options['default'] ?>") {
                        jQuery(this).iCheck("check");
                    } else {
                        jQuery(this).iCheck("uncheck");
                    }
                });
                <?php break; case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
                thisRichtxtId = "ymFormRichtext_<?= $field ?>";
                thisEditor = wangEditorMap[thisRichtxtId];
                if (thisEditor) {
                    thisEditor.txt.html("<?= $options['default'] ?>");
                }
                <?php break; case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
                customInitFunc = <?= $options['widget']->initValuesJsFunction() ?>;
                customInitFunc();
                <?php break; ?>
                <?php endswitch; ?>
                <?php endforeach; ?>

                $scope.ymFormFields = scopeFields;
            };
            // 清空表单
            var clearFormValus = function () {
                var scopeFields = {};
                var fileDefaults;
                var fileNumbers;
                var thisRichtxtId;
                var thisEditor;
                var customClearFunc;
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
                <?php case FieldsOptions::CONTROL_TEXTAREA: // 文本域 ?>
                scopeFields['<?= $field ?>'] = "";
                <?php break; case FieldsOptions::CONTROL_HIDDEN: // 隐藏 ?>
                scopeFields['<?= $field ?>'] = '<?= $options['default'] ?>';
                <?php break; case FieldsOptions::CONTROL_FILE: // 文件 ?>
                fileDefaults = [];
                fileNumbers = <?= $options['number'] ?>;
                for (var i = 0; i < fileNumbers; i++) {
                    fileDefaults[i] = "0";
                    // 初始化预览图
                    $scope['ymFormFileLink<?= $field ?>' + i] = "";
                }
                scopeFields['<?= $field ?>'] = fileDefaults.join(',');
                <?php break; case FieldsOptions::CONTROL_CHECKBOX: // 多选 ?>
                jQuery(".ymFormCheckbox_<?= $field ?>").each(function () {
                    jQuery(this).iCheck("uncheck");
                });
                <?php break; case FieldsOptions::CONTROL_RADIO: // 单选 ?>
                jQuery(".ymFormRadio_<?= $field ?>").each(function () {
                    jQuery(this).iCheck("uncheck");
                });
                <?php break; case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
                thisRichtxtId = "ymFormRichtext_<?= $field ?>";
                thisEditor = wangEditorMap[thisRichtxtId];
                if (thisEditor) {
                    thisEditor.txt.clear();
                }
                <?php break; case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
                customClearFunc = <?= $options['widget']->clearValuesJsFunction() ?>;
                customClearFunc();
                <?php break; ?>
                <?php endswitch; ?>
                <?php endforeach; ?>

                $scope.ymFormFields = scopeFields;
            };
            // 获取表单数据
            var getFormValus = function () {
                var formData = {};
                var checkboxTempMap;
                var thisRichtxtId;
                var thisEditor;
                var richtxtBody;
                var customGetValFunc;
                var customTempMap;
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
                <?php case FieldsOptions::CONTROL_FILE: // 文件 ?>
                formData['<?= $field ?>'] = $scope.ymFormFields['<?= $field ?>'];
                <?php break; case FieldsOptions::CONTROL_CHECKBOX: // 多选 ?>
                checkboxTempMap = [];
                jQuery(".ymFormCheckbox_<?= $field ?>").each(function () {
                    if (jQuery(this).is(":checked")) {
                        checkboxTempMap.push(jQuery(this).val());
                    }
                });
                formData['<?= $field ?>'] = checkboxTempMap.join(',');
                <?php break; case FieldsOptions::CONTROL_RADIO: // 单选 ?>
                // 设置给单选默认值。
                formData['<?= $field ?>'] = "";
                jQuery(".ymFormRadio_<?= $field ?>").each(function () {
                    if (jQuery(this).is(":checked")) {
                        formData['<?= $field ?>'] = jQuery(this).val();
                    }
                });
                <?php break; case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
                thisRichtxtId = "ymFormRichtext_<?= $field ?>";
                thisEditor = wangEditorMap[thisRichtxtId];
                if (thisEditor) {
                    richtxtBody = thisEditor.txt.html()
                    formData['<?= $field ?>'] = /^(<p><br><\/p>)+$/i.test(richtxtBody) ? "" : richtxtBody;
                }
                <?php break; case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
                customGetValFunc = <?= $options['widget']->getValuesJsFunction() ?>;
                customTempMap = customGetValFunc() || {};
                jQuery.extend(formData, customTempMap);
                <?php break; ?>
                <?php endswitch; ?>
                <?php endforeach; ?>

                return formData;
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
            // 清空表单
            $scope.ymFormClearForm = function () {
                clearFormValus();
            };
            // 重置表单
            $scope.ymFormResetForm = function () {
                initFormValues();
            };
            var submitForm = function () {
                var formData = getFormValus();
                var currentUrl = '<?= Url::current() ?>';
                // 节流
                var i = YmSpinner.show();
                $http.post(currentUrl, formData).then(function (data) {
                    YmSpinner.hide(i);
                    var result = data.data;
                    if (result.code == 200) {
                        tips(result.msg ? result.msg : '提交成功', '通知', 1, function () {
                            <?php if ($_autoBack): ?>
                            var referrer = window.document.referrer;
                            if (window.self !== window.top) {
                                // 在iframe中
                                // 先得到当前iframe层的索引
                                var index = parentLayer.getFrameIndex(window.name);
                                // 再执行关闭
                                parentLayer.close(index);
                                // 刷新父窗口
                                var mountedMethods = window.parent._EasyFrameModalSuccessCallback;
                                if (typeof mountedMethods !== "undefined") {
                                    for (var i in mountedMethods) {
                                        if (mountedMethods.hasOwnProperty(i) && typeof mountedMethods[i] == "function") {
                                            mountedMethods[i].call();
                                        }
                                    }
                                }
                            } else if (referrer) {
                                // 不在iframe中,如果存在来源则返回来源并刷新页面
                                window.self.location.href = referrer;
                            } else {
                                // 不存在来源则使用history
                                window.self.history.go(-1);
                            }
                            <?php endif; ?>
                        });
                    } else {
                        tips(result.msg ? result.msg : (result.code == 500 ? '提交失败' : '您没有权限操作!'), "通知", 5);
                    }
                }, function (errors) {
                    YmSpinner.hide(i);
                    console.error(errors);
                    tips(errors.data || "系统错误，请稍后重试!", "通知", 2);
                });
            };
            // 提交表单
            $scope.ymFormSubmitForm = function () {
                parentLayer.alert("是否确定当前操作?", {
                    closeBtn: 2,
                    title: "信息",
                    icon: 0,
                }, function (index) {
                    parentLayer.close(index);
                    submitForm();
                });
            };
            // 初始化表单[调用]
            ymInitForm();

            <?php foreach ($_innerScript as $_js): ?>
            <?= $_js ?>
            <?php endforeach; ?>

        }]);
    }(window, window._EasyApp);
</script>
