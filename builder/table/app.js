/**
 * table builder js
 * @author cleverstone
 * @since 1.0
 */
!function (window, angular) {
    "use strict";

    var $thisApp = angular.module("thisApp", ["$YmApp"]);
    $thisApp.controller('thisCtrl', [
        "$scope",
        "$http",
        "$timeout",
        "$interval",
        "$rootScope",
        "$YmApp",
        "$toastr",
        "$jq",
        "$yii",
        "$YmSpinner",
        "$swal",
        "$laydate",
        function (
            $scope,
            $http,
            $timeout,
            $interval,
            $rootScope,
            $YmApp,
            $toastr,
            $jq,
            $yii,
            $YmSpinner,
            $swal,
            $laydate
        ) {
            // ----- 列表start

            // 获取请求链接
            var link = '<?= $link ?>';
            $scope.getUrl = function (page, perPage, query) {
                page = page || $scope.pageNumber || 1;
                perPage = perPage || $scope.perPageNumber || 20;
                query = query || $scope.queryParams || {}

                $scope.pageNumber = page;
                $scope.perPageNumber = perPage;
                $scope.queryParams = query;

                var param = {
                    page: page,
                    'per-page': perPage,
                };

                /* 使用Jq的对象合并方案 */
                $jq.extend(param, query);
                return link + '?' + $jq.param(param);
            };

            // 获取数据列表
            $scope.getList = function (page, perPage, param) {
                // 节流
                var i = $YmSpinner.show();
                $http.get($scope.getUrl(page, perPage, param)).then(function (result) {
                    $YmSpinner.hide(i);

                    var data = result.data;
                    $scope.ymPage = data.page;
                    $scope.list = data.data;

                    $scope.cancalCheckboxChecked();
                }, function (error) {
                    $YmSpinner.hide(i);
                    $toastr.error(error.data || "数据加载失败，请稍后重试", "错误");
                    console.error(error);
                });
            };

            // 列表刷新时，取消多选框的选中状态
            $scope.cancalCheckboxChecked = function () {
                $YmApp.uncheckTableIcheck();
            };

            // 初始化方法
            ($scope.init = function () {
                $scope.getList();
            }());

            // 分页
            $scope.getPage = function (page, perPage) {
                $scope.getList(page, perPage);
            };

            // 跳转到指定页
            $scope.dumpSpecialPage = function (perPage) {
                var page = $scope.currentPage;
                $scope.getList(page, perPage);
            };

            // 设置数据条数
            $jq('body').on('change', '#pageSelect', function () {
                $scope.getList(1, $jq(this).val());
            });

            // 监听angular列表渲染完成
            $scope.$on('ev-repeat-finished', function () {
                // 初始化Icheck
                $YmApp.initTableIcheck();
            });

            // 行操作 - 解析参数
            $scope.resolveParams = function (data, params) {
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

            // 行操作入口
            $scope.rowActions = function (item, config) {
                config = $jq.parseJSON(config);
                var type = config.type;
                var options = config.options;
                var method = options.method || 'get';
                var params = options.params || [];
                var route = options.route;
                var title = options.title || '操作项';
                // 解析参数
                params = $scope.resolveParams(item, params);
                switch (type) {
                    case "page":
                        break;
                    case "modal":
                        break;
                    case "ajax":
                        $scope.ajaxRequest(method, params, route);
                        break;
                    default:
                        $toastr.warning("行类型" + type + "暂不支持", "警告");
                }
            };

            // 行操作 - ajax
            $scope.ajaxRequest = function (method, params, route) {
                $swal.fire({
                    title: '确定要执行该操作么？',
                    text: '',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: '确定',
                    cancelButtonText: '取消'
                }).then(function (result) {
                    if (result.value) {
                        var flag = $YmSpinner.show("操作执行中,请稍后...");

                        var instance;
                        if (method === "get") {
                            instance = $http.get(route + '?' + $jq.param(params));
                        } else if (method === "post") {
                            instance = $http.post(route, $jq.param(params));
                        }

                        instance.then(function (result) {
                            $YmSpinner.hide(flag);
                            var data = result.data;
                            if (data.code === 0) {
                                $toastr.success(data.msg, "成功");
                                $scope.getList();
                            } else {
                                $toastr.warning(data.msg, "警告");
                            }

                        }, function (error) {
                            $YmSpinner.hide(flag);
                            $toastr.error(error.data || "操作执行失败", "错误");
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

            // ----- 列表end

            // ------ 工具栏 start
            // 选中删除
            $scope.deleteSelected = function () {
                var data = $YmApp.getTableCheckedData();
            };

        }]);
}(window, window.angular);