/**
 * Yii-manager angular service register
 * @author cleverstone
 * @since 1.0
 */
!function (global, angular) {
    "use strict";

    var $YmApp = angular.module("$YmApp", []);

    // 自定义服务
    $YmApp.service("$YmApp", function () {
        // YmApp
        return global.YmApp;
    }).service("$toastr", function () {
        // Toastr
        return global.toastr;
    }).service("$jq", function () {
        // jQuery 3
        return global.jQuery;
    }).service("$yii", function () {
        // Yii 2.0
        return global.yii;
    }).service("$YmSpinner", function () {
        // Spinner
        return global.YmSpinner;
    }).service("$swal", function () {
        // Sweetalert
        return global.Swal;
    }).service("$laydate", function () {
        // Laydate
        return global.laydate;
    });

    // 自定义过滤器
    $YmApp.filter("toHtml", ['$sce', function ($sce) {
        return function (value) { // 过滤html
            return $sce.trustAsHtml(value);
        };
    }]).filter("default", function () {
        return function (value, defaultVal) { // 默认值
            return value || defaultVal;
        };
    });

    // 自定义指令
    $YmApp.directive('angularajaxpage', function ($compile) {
        // 分页指令
        return {
            restrict: 'A',
            replace: true,
            scope: false,
            link: function (scope, element, attrs) {
                scope.$watch(
                    function (scope) {
                        return scope.$eval(attrs.angularmethod);
                    },
                    function (value) {
                        element.html(value);
                        $compile(element.contents())(scope);
                    }
                );
            }
        };
    });

    // 服务驱动配置
    $YmApp.factory("httpInterceptor", ["$q", function ($q) {
        // http拦截器
        return {
            'responseError': function (response) { // 错误拦截
                return $q.reject(response);
            },
            'response': function (response) {     // 响应拦截
                return response;
            },
            'request': function (config) {        // 请求拦截
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

}(window, window.angular);