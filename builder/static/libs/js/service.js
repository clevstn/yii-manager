/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

/**
 * Yii-manager angular service register
 * @author cleverstone
 * @since 1.0
 */
!function (global, angular) {
    "use strict";

    var _YmAppModule = angular.module("YmAppModule", ["ui.select2"]);

    // 自定义服务
    _YmAppModule.factory("YmApp", function () {
        // YmApp
        return global.YmApp;
    }).factory("toastr", function () {
        // Toastr
        return global.toastr;
    }).factory("jQuery", function () {
        // jQuery 3
        return global.jQuery;
    }).factory("yii", function () {
        // Yii 2.0
        return global.yii;
    }).factory("YmSpinner", function () {
        // Spinner
        return global.YmSpinner;
    }).factory("Swal", function () {
        // Sweetalert
        return global.Swal;
    }).factory("laydate", function () {
        // Laydate
        return global.laydate;
    }).factory("layer", function () {
        // Layer
        return global.layer;
    }).factory("wangEditor", function () {
        // wangEditor
        return global.wangEditor;
    });

    // 自定义过滤器
    _YmAppModule.filter("toHtml", ['$sce', function ($sce) {
        return function (value) { // 过滤html
            return $sce.trustAsHtml(value);
        };
    }]).filter("default", function () {
        return function (value, defaultVal) { // 默认值
            return value || defaultVal;
        };
    });

    // 自定义指令
    _YmAppModule.directive('angularAjaxPage', ["$compile", function ($compile) {
        // 分页指令
        return {
            restrict: 'A',
            replace: true,
            scope: false,
            link: function (scope, element, attrs) {
                scope.$watch(
                    function (scope) {
                        return scope.$eval(attrs.pageModel);
                    },
                    function (value) {
                        element.html(value);
                        $compile(element.contents())(scope);
                    }
                );
            }
        };
    }]).directive('onFinishRender', ["$timeout", function ($timeout) {
        // 监听ng-repeat执行完成指令
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if (scope.$last === true) {
                    $timeout(function () {
                        // ev-repeat-finished
                        scope.$emit(attr.onFinishRender);
                    });
                }
            }
        };
    }]).directive('stringToNumber', function() {
        // 当前input控件是number时，自动转换ngModel的数据类型。
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(value) {
                    return '' + value;
                });
                ngModel.$formatters.push(function(value) {
                    return parseFloat(value);
                });
            }
        };
    });

    // 服务驱动配置
    _YmAppModule.factory("httpInterceptor", ["$q", function ($q) {
        // http拦截器
        return {
            'responseError': function (response) { // 错误拦截
                return $q.reject(response);
            },
            'response': function (response) {     // 响应拦截
                return response;
            },
            'request': function (config) {        // 请求拦截
                // 当时请求方法是`POST`时,自动携带[csrf]令牌,并自动参数序列化.
                var method = config.method;
                var bodyParams = config.data || {};
                if (method.toUpperCase() === 'POST') {
                    bodyParams[global.yii.getCsrfParam()] = global.yii.getCsrfToken();
                    config.data = global.jQuery.param(bodyParams);
                }

                return config;
            },
            'requestError': function (config) {    // 请求错误拦截
                return $q.reject(config);
            }
        };
    }]).config(["$httpProvider", function ($httpProvider) {
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        $httpProvider.interceptors.push('httpInterceptor'); //添加拦截器
    }]);

    // 注册App
    global._EasyApp = angular.module("_EasyApp", ["YmAppModule", "ngFileUpload"]);


}(window, window.angular);