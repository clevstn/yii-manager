<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/* @var string $link 列表链接 */
/* @var boolean $isPage 是否是分页 */
/* @var array $filterCustoms 筛选自定义控件选项 */
/* @var array $filterColumns 筛选表单选项 */
/* @var array $innerScript 额外Js脚本 */

// 注意这里必须是<script>...</script>的形式
?>
<script>
    /**
     * table builder script
     * @author cleverstone
     * @since ym1.0
     */
    !function (window, _EasyApp) {
        "use strict";
        _EasyApp.controller('_EasyApp_tableCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui",function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
            // ------ 列表 start ------
            var link = '<?= $link ?>';
            var isPageList = <?= $isPage ?>;
            var pageNumber;
            var perPageNumber;
            var queryParams;

            // 获取当前构建器操作链接
            var getUrl = function (page, perPage, query) {
                page = page || pageNumber || 1;
                perPage = perPage || perPageNumber || 20;
                query = query || queryParams || {};

                pageNumber = page;
                perPageNumber = perPage;
                queryParams = query;

                var param = isPageList ? {
                    page: page,
                    'per-page': perPage
                } : {};

                /* 使用Jq的对象合并方案 */
                jQuery.extend(param, query);
                return YmApp.addUrlQueryParam(link, param);
            };
            // 获取表格列表
            var getTableList = function (page, perPage, param) {
                // 节流
                var i = YmSpinner.show();
                // 初始化页面
                $scope.isEmptyOfTable = false;

                $http.get(getUrl(page, perPage, param)).then(function (result) {
                    YmSpinner.hide(i);
                    var data = result.data;
                    $scope.tablePaging = data.page || '';
                    $scope.tableListData = data.data || [];
                    if ($scope.tableListData.length <= 0) {
                        // 显示空
                        $scope.isEmptyOfTable = true;
                    }
                    // 列表刷新时，取消多选框的选中状态
                    YmApp.uncheckTableIcheck();
                }, function (error) {
                    YmSpinner.hide(i);
                    // 显示空
                    $scope.isEmptyOfTable = true;
                    toastr.error(error.data || "数据加载失败，请稍后重试", "通知");
                    console.error(error);
                });
            };

            // 挂载到window._EasyApp_ParentTableRefresh
            if (typeof window._EasyApp_ParentTableRefresh !== "undefined") {
                window._EasyApp_ParentTableRefresh.getTableList = getTableList;
            } else {
                window._EasyApp_ParentTableRefresh = {
                    getTableList: getTableList,
                };
            }

            // 初始化表格
            var initTableList = function () {
                // 初始化页面
                $scope.isEmptyOfTable = false;
                // 初始化导出列表
                $scope.tableExportList = [];

                // 初始化筛选表单中的日期控件
                jQuery(".ymTablefilterDate").each(function () {

                    var id = jQuery(this).attr('id');
                    var range = jQuery(this).attr('range');
                    var tag = jQuery(this).attr('tag');

                    var options = {
                        elem: "#" + id,
                        type: tag,
                        calendar: true,
                        done: function (value, date, endDate) {
                            /* 触发input事件 */
                            jQuery(this.elem).val(value).trigger("change");
                        }
                    };

                    if (range === '1') {
                        options.range = '/';
                    }

                    laydate.render(options);
                });

                // 初始化筛选
                <?php foreach ($filterCustoms['initScript'] as $i => $jsFunction): ?>
                // custom
                var initScript<?= $i ?> = <?= $jsFunction ?>;
                initScript<?= $i ?>();
                <?php endforeach; ?>

                $scope.tableFilterData = <?= $filterColumns ?>;

                // 初始化列表
                getTableList();
            };
            // 分页跳转
            $scope.triggerTableDumpPage = function (page, perPage) {
                getTableList(page, perPage);
            };
            // 跳转到指定页
            $scope.triggerTableDumpSpecialPage = function (perPage) {
                var page = $scope.tableCurrentPage;
                getTableList(page, perPage);
            };
            // 设置数据条数
            jQuery('body').on('change', '#YmTablePageSelect', function () {
                getTableList(1, jQuery(this).val());
            });
            // 监听表格列表渲染完成
            $scope.$on('ev-repeat-finished', function () {
                // 初始化Icheck
                YmApp.initTableIcheck();
            });
            // 表格行操作-解析参数
            var resolveActionParams = function (data, params) {
                var to = {};
                for (var i in params) {
                    if (i % 1 === 0) {
                        // 从data中获取参数值
                        to[params[i]] = (data[params[i]] === void 0 ? '' : data[params[i]]);
                    } else {
                        to[i] = params[i];
                    }
                }

                return to;
            };
            // 表格行操作-打开模态框
            var openModalOnRow = function (title, params, route, width, height) {
                var layerParams = YmApp.layerParseParams(width);
                var u = YmApp.addUrlQueryParam(route, params);

                layer.open({
                    type: 2,
                    shade: 0.3,
                    anim: -1,
                    title: title,
                    maxmin: false,
                    shadeClose: false,
                    closeBtn: layerParams.closeBtn,
                    area: [layerParams.width, height],
                    content: u,
                });
            };
            // 表格行操作-打开页面
            var openPageOnRow = function (title, params, route) {
                params['pageTitle'] = title;
                var u = YmApp.addUrlQueryParam(route, params);

                window.location.href = u;
            };
            // 表格行操作-ajax请求
            var ajaxRequestOnRow = function (method, params, route) {
                Swal.fire({
                    title: '确定要执行该操作么？',
                    text: '',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消'
                }).then(function (result) {
                    if (result.value) {
                        var flag = YmSpinner.show("操作执行中,请稍后...");

                        var instance;
                        if (method === "get") {
                            var u = YmApp.addUrlQueryParam(route, params);

                            instance = $http.get(u);
                        } else if (method === "post") {
                            instance = $http.post(route, params);
                        }

                        instance.then(function (result) {
                            YmSpinner.hide(flag);
                            var data = result.data;
                            if (data.code === 200) {
                                toastr.success(data.msg, "通知");
                                // reload list
                                $timeout(function () {
                                    getTableList();
                                }, 150);
                            } else if (data.code === 500) {
                                toastr.warning(data.msg, "通知");
                            } else if (data.code === 401) {
                                toastr.error(data.msg, "通知");
                            } else {
                                toastr.error(data.msg, "通知");
                            }

                        }, function (error) {
                            YmSpinner.hide(flag);
                            toastr.error(error.data || "操作执行失败", "通知");
                            console.error(error);
                        });
                        // For more information about handling dismissals please visit
                        // https://sweetalert2.github.io/#handling-dismissals
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // cancel
                        // ...
                    }
                });
            };
            // 表格行操作-入口
            $scope.triggerTableRowActions = function (item, config) {
                config = config || {};
                var type = config.type;
                var options = config.options;
                var method = options.method || 'get';
                var params = options.params || {};
                var route = options.route;
                var title = options.title || '默认标题';
                var width = options.width || '800px';
                var height = options.height || '520px';

                // 解析参数
                params = resolveActionParams(item, params);
                switch (type) {
                    case "page":
                        openPageOnRow(title, params, route);
                        break;
                    case "modal":
                        openModalOnRow(title, params, route, width, height);
                        break;
                    case "ajax":
                        ajaxRequestOnRow(method, params, route);
                        break;
                    default:
                        toastr.warning("行类型" + type + "暂不支持", "通知");
                }
            };
            // ------ 列表 end ------

            // ------ 工具栏 start ------
            // 表格筛选
            $scope.triggerTableFilterMethod = function () {
                var layerParams = YmApp.layerParseParams('750px');

                layer.open({
                    type: 1,
                    shade: 0.3,
                    anim: -1,
                    title: '筛选',
                    maxmin: false,
                    shadeClose: false,
                    closeBtn: layerParams.closeBtn,
                    area: [layerParams.width],
                    btn: ['确定筛选', '清空'],
                    yes: function (index, layero) {
                        var param = $scope.tableFilterData;

                        // custom
                        <?php foreach ($filterCustoms['getScript'] as $i => $jsFunction): ?>
                        var getScript<?= $i ?> = <?= $jsFunction ?>;
                        var value = getScript<?= $i ?>();
                        if (YmApp.typeOf(value) !== 'object') {
                            value = {};
                        }

                        jQuery.extend(param, value);
                        <?php endforeach; ?>

                        // 提交筛选
                        $scope.$apply(function () {
                            getTableList(1, null, param);
                        });
                        layer.close(index);
                    },
                    btn2: function (index, layero) {
                        // 清空筛选
                        $scope.$apply(function () {
                            var tempObj = {};
                            for (var i in $scope.tableFilterData) {
                                tempObj[i] = "";
                            }

                            $scope.tableFilterData = tempObj;

                            // custom
                            <?php foreach ($filterCustoms['clearScript'] as $i => $jsFunction): ?>
                            var clearScript<?= $i ?> = <?= $jsFunction ?>;
                            clearScript<?= $i ?>();
                            <?php endforeach; ?>

                        });
                        return false;
                    },
                    content: jQuery("#_EasyApp_tableFilterForm"),
                });
            };
            // 表格列表导出
            $scope.triggerTableExportMethod = function () {
                var query = jQuery.extend({}, queryParams || {});
                query['__export'] = 1;
                var u = YmApp.addUrlQueryParam(link, query);

                // 节流
                var i = YmSpinner.show();

                $http.get(u).then(function (result) {

                    YmSpinner.hide(i);

                    var data = result.data;
                    if (data.length) {
                        var tempMap = [];
                        data.forEach(function (value) {
                            query['_offset'] = value.offset;
                            query['_limit'] = value.limit;
                            query['_filename'] = value.filename;

                            var u = YmApp.addUrlQueryParam(link, query);

                            tempMap.push({
                                page: value.page,
                                rows: value.rows,
                                url: u,
                            });
                        });

                        $scope.tableExportList = tempMap;
                        var layerParams = YmApp.layerParseParams('500px');

                        layer.open({
                            type: 1,
                            shade: 0.3,
                            anim: -1,
                            title: '导出',
                            maxmin: false,
                            shadeClose: false,
                            closeBtn: layerParams.closeBtn,
                            area: [layerParams.width, '550px'],
                            content: jQuery("#YmExportForm"),
                        });
                    } else {
                        toastr.info('数据列表为空,没有需要导出的数据!', "通知");
                    }
                }, function (error) {
                    YmSpinner.hide(i);
                    toastr.error(error.data || "数据导出列表加载失败，请稍后重试", "通知");
                    console.error(error);
                });

            };
            // 标记已导出
            $scope.triggerTableFlagExport = function (e) {
                var elem = e.currentTarget;
                $timeout(function () {
                    jQuery(elem).text("重新导出");
                });
            };
            // 自定义操作项-解析参数
            var resolveRequestParams = function (data, params) {
                var to = {};
                for (var i in params) {
                    if (i % 1 === 0) {
                        var tempMap = [];
                        data.forEach(function (value) {
                            // 从data中获取参数值
                            var currentValue = value[params[i]] === void 0 ? '' : value[params[i]];
                            tempMap.push(currentValue)
                        });

                        to[params[i]] = tempMap.join(',');
                    } else {
                        to[i] = params[i];
                    }
                }

                return to;
            };

            // 自定义操作项-打开模态框
            var openModalOnToolbar = function (title, params, route, width, height) {
                var layerParams = YmApp.layerParseParams(width);
                var u = YmApp.addUrlQueryParam(route, params);

                layer.open({
                    type: 2,
                    shade: 0.3,
                    anim: -1,
                    title: title,
                    maxmin: false,
                    shadeClose: false,
                    closeBtn: layerParams.closeBtn,
                    area: [layerParams.width, height],
                    content: u,
                });
            };
            // 自定义操作项-打开页面
            var openPageOnToolbar = function (title, params, route) {
                params['pageTitle'] = title;
                window.location.href = YmApp.addUrlQueryParam(route, params);
            };
            // 自定义操作项-ajax请求
            var ajaxRequestOnToolbar = function (method, params, route) {
                Swal.fire({
                    title: '确定要执行该操作么？',
                    text: '',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消'
                }).then(function (result) {
                    if (result.value) {
                        var flag = YmSpinner.show("操作执行中,请稍后...");

                        var instance;
                        if (method === "get") {
                            var u = YmApp.addUrlQueryParam(route, params);
                            instance = $http.get(u);
                        } else if (method === "post") {
                            instance = $http.post(route, params);
                        }

                        instance.then(function (result) {
                            YmSpinner.hide(flag);
                            var data = result.data;
                            if (data.code === 200) {
                                toastr.success(data.msg, "通知");
                                // reload list
                                $timeout(function () {
                                    getTableList();
                                }, 150);
                            } else if (data.code === 500) {
                                toastr.warning(data.msg, "通知");
                            } else if (data.code === 401) {
                                toastr.error(data.msg, "通知");
                            } else {
                                toastr.error(data.msg, "通知");
                            }

                        }, function (error) {
                            YmSpinner.hide(flag);
                            toastr.error(error.data || "操作执行失败", "通知");
                            console.error(error);
                        });
                        // For more information about handling dismissals please visit
                        // https://sweetalert2.github.io/#handling-dismissals
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // cancel
                        // ...
                    }
                });
            };
            // 自定义操作项-入口
            $scope.triggerTableCustomMethod = function (options) {
                var data = YmApp.getTableCheckedData() || [];
                options = options || {};

                var type = options.option;
                var method = options.method || 'get';
                var params = options.params || {};
                var route = options.route;
                var title = options.title || '默认标题';
                var width = options.width || '800px';
                var height = options.height || '520px';

                var notAllUserDefinedParam = false;
                for (var i in params) {
                    if (i % 1 === 0) {
                        // 存在变量参数
                        notAllUserDefinedParam = true;
                    }
                }

                if (notAllUserDefinedParam && !data.length) {
                    layer.alert("请选择数据列", {
                        title: "提示",
                    });

                    return true;
                }

                // 解析参数
                params = resolveRequestParams(data, params);
                switch (type) {
                    case "page":
                        openPageOnToolbar(title, params, route);
                        break;
                    case "modal":
                        openModalOnToolbar(title, params, route, width, height);
                        break;
                    case "ajax":
                        ajaxRequestOnToolbar(method, params, route);
                        break;
                    default:
                        toastr.warning("工具栏操作类型" + type + "暂不支持", "通知");
                }
            };
            // ------ 工具栏 end ------

            // 初始化表格
            initTableList();

            <?php foreach ($innerScript as $js): ?>
            <?= $js ?>
            <?php endforeach; ?>

        }]);
    }(window, window._EasyApp);
</script>
