/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * 管理组权限分配
 * @author cleverstone
 * @since ym1.0
 */

!function (window, _EasyApp) {
    "use strict";
    _EasyApp.controller('dispatchCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
        layui.use(['tree', 'util'], function () {
            var tree = layui.tree;
            var util = layui.util;
            // 渲染
            tree.render({
                elem: '#PermissionNodeTree',
                // 是否仅允许节点左侧图标控制展开收缩。默认 false（即点击节点本身也可控制）。若为 true，则只能通过节点左侧图标来展开收缩
                //onlyIconControl: true,
                /* window.YmData在dispatch.php中定义 */
                data: window.YmData.dispatchPermissionData,
                showCheckbox: true,  //是否显示复选框
                id: 'PermissionNodeID'
            });

            // 提交和重置
            util.event('lay-tree', {
                // 提交
                getChecked: function () {
                    //获取选中节点的数据
                    var checkedData = tree.getChecked('PermissionNodeID');
                    if (checkedData.length <= 0) {
                        parent.layer.alert("请选择权限", {
                            title: "提示"
                        });
                        return false;
                    }

                    function recursive(data, checkedId) {
                        data = data || [];
                        data.forEach(function (item) {
                            checkedId.push(item.id);
                            recursive(item.children, checkedId)
                        });
                    }

                    var checkedIds = [];
                    recursive(checkedData, checkedIds);

                    var i = YmSpinner.show();
                    $http.post(window.location.href, {menuIds : checkedIds}).then(function (data) {
                        YmSpinner.hide(i);
                        var result = data.data;
                        if (result.code == 200) {
                            window.parent.layer.alert(result.msg ? result.msg : '提交成功', {
                                closeBtn: 0,
                                title: '通知',
                                icon: 1
                            }, function (index) {
                                window.parent.layer.close(index);
                                // 在iframe中,先得到当前iframe层的索引,再执行关闭
                                window.parent.layer.close(window.parent.layer.getFrameIndex(window.name));
                                // 刷新父窗口
                                var mountedMethods = window.parent._EasyApp_ParentTableRefresh;
                                if (typeof mountedMethods !== "undefined") {
                                    for (var i in mountedMethods) {
                                        if (mountedMethods.hasOwnProperty(i) && typeof mountedMethods[i] == "function") {
                                            mountedMethods[i].call();
                                        }
                                    }
                                }
                            });
                        } else {
                            window.parent.layer.alert(result.msg ? result.msg : (result.code == 500 ? '提交失败' : '您没有权限操作!'), {
                                closeBtn: 0,
                                title: '通知',
                                icon: 5
                            }, function (index) {
                                window.parent.layer.close(index);
                            });
                        }
                    }, function (errors) {
                        YmSpinner.hide(i);
                        window.console.error(errors);
                        window.parent.layer.alert(errors.data || "系统错误，请稍后重试!", {
                            closeBtn: 0,
                            title: '通知',
                            icon: 2
                        }, function (index) {
                            window.parent.layer.close(index);
                        });
                    });
                },
                // 重置
                reload: function () {
                    // 初始化tree
                    tree.reload('PermissionNodeID', {});
                },
                // 关闭当前窗口
                close: function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                }
            });
        });

    }]);
}(window, window._EasyApp);