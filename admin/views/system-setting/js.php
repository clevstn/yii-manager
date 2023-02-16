<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */
/* @var array $config  配置项：group => [code => ...]  */
/* @var array $group  分组项 */

use app\models\SystemConfig as Sc;
use app\components\Uploads;
use yii\helpers\Url;

?>
<script>
    /**
     *
     * @copyright Copyright (c) 2020 cleverstone
     *
     * 系统配置
     * @author cleverstone
     * @since ym1.0
     */

    !function (window, _EasyApp) {
        "use strict";
        _EasyApp.controller('_EasyApp_SystemConfigCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", 'wangEditor', function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui, wangEditor) {
            var parentLayer = window.parent.layer;
            // 初始化
            var initAll = function () {
                mountedLaydate();       // 挂载日期插件
                mountedWangEditor();    // 挂载富文本插件

                initFormItem();         // 初始化表单项
            };
            // 挂载日期插件
            var mountedLaydate = function () {
                jQuery(".ymSysConfDates").each(function () {
                    var id = jQuery(this).attr('id');
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

                    laydate.render(options);
                });
            };

            // 挂载富文本插件
            var wangEditorMap = {};
            var mountedWangEditor = function () {
                if (typeof wangEditor !== "undefined") {
                    jQuery(".YmSysConfWangEditor").each(function () {
                        var id = jQuery(this).attr('id');
                        var editor = new wangEditor(this);
                        editor.create();
                        wangEditorMap[id] = editor;
                    });
                }
            };

            // 上传图片
            $scope.triggerSelectImage = function (code) {
                var queryParam = {
                    'name': 'file',
                    'type': '系统设置',
                    'save_directory': 'system_setting',
                    'path_prefix': code,
                    'scenario': '<?= Uploads::SCENARIO_IMAGE ?>',
                    '_': YmApp.getTime()
                };
                var u = YmApp.addUrlQueryParam(YmApp.$adminApi.fileUploadUrl, queryParam);

                var layerParams = YmApp.layerParseParams('620px');
                window.layer.open({
                    type: 2,
                    shade: 0.3,
                    anim: -1,
                    title: '附件管理',
                    maxmin: false,
                    shadeClose: false,
                    btn: ['确认选择', '取消'],
                    closeBtn: layerParams.closeBtn,
                    area: [layerParams.width, '780px'],
                    content: u,
                    yes: function(i, layero){
                        var win = window[layero.find('iframe')[0]['name']];
                        var choose = win._EasyApp_UploadChooseAttachments();
                        if (choose.length <= 0) {
                            YmApp._layerTip('请您选择一张图片', '提示', 0);
                            return;
                        }

                        if (choose.length > 1) {
                            YmApp._layerTip('只能选择一张，禁止多张', '提示', 0);
                            return;
                        }

                        // 更新该字段值
                        choose.forEach(function (item) {
                            $scope[code] = item.id;
                            // 预览图
                            $scope.$apply(function () {
                                // 预览图
                                $scope[code + '_preview'] = item.url;
                            });
                        });

                        window.layer.close(i);
                    },
                    btn2: function (i) {
                        window.layer.close(i);
                    }
                });
            };

            // 监听Checkbox、Radio、Switch
            layui.use(['form'], function () {
                var form = layui.form;

                <?php foreach ($config as $group => $item): ?>
                <?php foreach ($item as $code => $value): ?>
                <?php switch ($value['control']): case Sc::CHECKBOX: //多选?>
                form.on('checkbox(<?= $code ?>)', function(data){
                    //console.log(data.elem); //得到checkbox原始DOM对象
                    //console.log(data.elem.checked); //是否被选中，true或者false
                    //console.log(data.value); //复选框value值，也可以通过data.elem.value得到
                    //console.log(data.othis); //得到美化后的DOM对象
                    var val = $scope['<?= $code ?>'];
                    var valArray = (val !== void 0 && val !== '') ? val.split(',') : [];
                    valArray.forEach(function (item, index) {
                        valArray[index] = jQuery.trim(item);
                    });

                    var i = jQuery.inArray(data.value, valArray);
                    if (i > -1) {
                        // 存在
                        if (!data.elem.checked) {
                            // 没选中
                            valArray.splice(i, 1);
                        }
                    } else {
                        // 不存在
                        if (data.elem.checked) {
                            // 选中
                            valArray.push(data.value);
                        }
                    }

                    val = valArray.join(',');

                    $scope.$apply(function () {
                        $scope['<?= $code ?>'] = val;
                    });
                });
                <?php break; case Sc::SW: //开关?>
                form.on('switch(<?= $code ?>)', function(data){
                    // console.log(data.elem); //得到checkbox原始DOM对象
                    // console.log(data.elem.checked); //开关是否开启，true或者false
                    // console.log(data.value); //开关value值，也可以通过data.elem.value得到
                    // console.log(data.othis); //得到美化后的DOM对象
                    $scope.$apply(function () {
                        $scope['<?= $code ?>'] = data.elem.checked ? 1 : 0;
                    });
                });
                <?php break; case Sc::RADIO: //单选?>
                form.on('radio(<?= $code ?>)', function(data){
                    // console.log(data.elem); //得到radio原始DOM对象
                    // console.log(data.value); //被点击的radio的value值
                    $scope.$apply(function () {
                        $scope['<?= $code ?>'] = data.value;
                    });
                });
                <?php break; endswitch; ?>
                <?php endforeach; ?>
                <?php endforeach; ?>
            });

            // 初始化表单项
            var initFormItem = function (group) {
                var initForm = group || 'all';
                <?php foreach ($config as $group => $item): ?>
                if (initForm === 'all' || initForm === '<?= $group ?>') {
                    <?php foreach ($item as $code => $value): ?>
                    <?php switch ($value['control']): case Sc::TEXT: //文本?>
                    <?php case Sc::NUMBER: //数字 ?>
                    <?php case Sc::PASSWORD: //密码?>
                    <?php case Sc::STATIC_: // 静态文本?>
                    <?php case Sc::DATETIME: //日期?>
                    <?php case Sc::DATE: //日期?>
                    <?php case Sc::YEAR: //年?>
                    <?php case Sc::MONTH: //月?>
                    <?php case Sc::TIME: //时间?>
                    <?php case Sc::RANGE: //范围?>
                    <?php case Sc::TEXTAREA: //文本域?>
                    <?php case Sc::SELECT: //下拉选择?>
                    $scope['<?= $code ?>'] = '<?= $value['value'] ?>';
                    // end
                    <?php break; case Sc::FILE: ?>
                    $scope['<?= $code ?>'] = '<?= $value['value'] ?>';
                    $scope['<?= $code ?>_preview'] = '<?= attach_url($value['value']) ?>';
                    <?php break; case Sc::RICHTEXT: ?>
                    var thisEditor = wangEditorMap['<?= $code ?>'];
                    if (thisEditor) {
                        thisEditor.txt.html("<?= $value['value'] ?>");
                    }
                    <?php break; case Sc::CHECKBOX: ?>
                    $scope['<?= $code ?>'] = '<?= $value['value'] ?>';
                    // 处理checkbox值
                    <?php $thisVal = explode(',', $value['value']); ?>
                    // 该form域中checkbox初始化为false
                    jQuery('input[lay-filter="<?= $code ?>"]').prop('checked', false);
                    // 遍历匹配选中的值
                    <?php foreach ($thisVal as $i): ?>
                    jQuery('input[name="<?= $code ?>[<?= trim($i) ?>]"]').prop('checked', true);
                    <?php endforeach; ?>
                    // 更新Checkbox Node
                    layui.form.render('checkbox', '<?= $code ?>');
                    <?php break; case Sc::RADIO: ?>
                    $scope['<?= $code ?>'] = '<?= $value['value'] ?>';
                    layui.form.val('<?= $code ?>', {
                        '<?= $code ?>': '<?= $value['value'] ?>',
                    });
                    <?php break; case Sc::SW: ?>
                    $scope['<?= $code ?>'] = <?= $value['value'] ?: 0 ?>;
                    layui.form.val('<?= $code ?>', {
                        '<?= $code ?>': <?= $value['value'] ?: 0 ?>,
                    });
                    <?php break; case Sc::CUSTOM: ?>
                    // 自定义...
                    <?php break; endswitch; ?>
                    <?php endforeach; ?>
                }
                <?php endforeach; ?>
            };

            // 获取表单项
            var getFormItem = function (group) {
                var initForm = group || 'all';
                var fromData = {};
                <?php foreach ($config as $group => $item): ?>
                if (initForm === 'all' || initForm === '<?= $group ?>') {
                    <?php foreach ($item as $code => $value): ?>
                    <?php switch ($value['control']): case Sc::TEXT: //文本?>
                    <?php case Sc::NUMBER: //数字 ?>
                    <?php case Sc::PASSWORD: //密码?>
                    <?php case Sc::STATIC_: // 静态文本?>
                    <?php case Sc::DATETIME: //日期?>
                    <?php case Sc::DATE: //日期?>
                    <?php case Sc::YEAR: //年?>
                    <?php case Sc::MONTH: //月?>
                    <?php case Sc::TIME: //时间?>
                    <?php case Sc::RANGE: //范围?>
                    <?php case Sc::TEXTAREA: //文本域?>
                    <?php case Sc::SELECT: //下拉选择?>
                    <?php case Sc::FILE: //文件?>
                    <?php case Sc::CHECKBOX: //多选?>
                    <?php case Sc::RADIO: //单选?>
                    <?php case Sc::SW: //开关?>
                    fromData['<?= $code ?>'] = $scope['<?= $code ?>'];
                    // end
                    <?php break; case Sc::RICHTEXT: ?>
                    var thisEditor = wangEditorMap['<?= $code ?>'];
                    fromData['<?= $code ?>'] = '';
                    if (thisEditor) {
                        var richtxtBody = thisEditor.txt.html();
                        fromData['<?= $code ?>'] = /^(<p><br><\/p>)+$/i.test(richtxtBody) ? '' : richtxtBody;
                    }
                    <?php break; case Sc::CUSTOM: ?>
                    // 自定义...
                    <?php break; endswitch; ?>
                    <?php endforeach; ?>
                }
                <?php endforeach; ?>

                return fromData;
            };

            // 重置
            $scope.triggerResetForm = function (group) {
                initFormItem(group);
            };

            // 提交
            $scope.triggerSubmitForm = function (group) {
                var formData = getFormItem(group);
                parentLayer.alert("是否确定当前操作?", {
                    closeBtn: 2,
                    title: "信息",
                    icon: 0,
                }, function (index) {
                    parentLayer.close(index);

                    var currentUrl = '<?= Url::current() ?>';
                    var i = YmSpinner.show();
                    $http.post(currentUrl, formData).then(function (data) {
                        YmSpinner.hide(i);
                        var result = data.data;
                        if (result.code == 200) {
                            YmApp._layerTip(result.msg ? result.msg : '提交成功', '通知', 1);
                        } else {
                            YmApp._layerTip(result.msg ? result.msg : (result.code == 500 ? '提交失败' : '您没有权限操作!'), "通知", 5);
                        }
                    }, function (errors) {
                        YmSpinner.hide(i);
                        console.error(errors);
                        YmApp._layerTip(errors.data || "系统错误，请稍后重试!", "通知", 2);
                    });
                });
            };

            // 初始化
            initAll();
        }]);
    }(window, window._EasyApp);
</script>

