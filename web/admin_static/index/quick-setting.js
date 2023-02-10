/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * 后台首页 - 快捷操作设置
 * @author cleverstone
 * @since ym1.0
 */
!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('_quickSettingCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
        var parentLayer = window.parent.layer;

        // 加入快捷项
        layui.use('form', function () {
            var form = layui.form;
            form.on('checkbox(inQuickItemFilter)', function(data){
                var isChecked = data.elem.checked ? 1 : 2;
                var menuId = data.value;

                var i = YmSpinner.show();
                $http.post(YmApp.$adminApi.indexQuickActionUrl, {
                        menu_id: menuId,
                        isChecked: isChecked
                    }).then(function (data) {
                    YmSpinner.hide(i);
                    var result = data.data;
                    var msg = result.msg || '提交成功';
                    if (result.code == 200) {
                        YmApp._layerTip(msg, '通知', 1, function () {
                            // 刷新父窗口
                            var mountedMethods = window.parent._Easy_QuickActionRefresh;
                            if (typeof mountedMethods !== "undefined") {
                                for (var i in mountedMethods) {
                                    if (mountedMethods.hasOwnProperty(i) && typeof mountedMethods[i] == "function") {
                                        mountedMethods[i].call();
                                    }
                                }
                            }
                        });
                    } else {
                        msg = result.msg || (result.code == 500 ? '提交失败' : '您没有权限操作!');
                        YmApp._layerTip(msg, "通知", 5);
                    }
                }, function (errors) {
                    YmSpinner.hide(i);
                    window.console.error(errors);
                    YmApp._layerTip(errors.data || "系统错误，请稍后重试!", "通知", 2);
                });



            });
        });
    }]);
}(window, window._EasyApp);