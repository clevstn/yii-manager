/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/**
 * Yii-manager angular service register
 * @author cleverstone
 * @since ym1.0
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
        return global.layui.laydate;
    }).factory("layer", function () {
        // Layer
        return global.layui.layer;
    }).factory("layui", function () {
        // LayUI
        return global.layui;
    }).factory("wangEditor", function () {
        // wangEditor
        return global.wangEditor;
    }).factory("lang", function () {
        // YmLang
        return global.YmLang || function (message) {
            return message;
        };
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
                // 输出转换为字符串
                ngModel.$parsers.push(function(value) {
                    if (value !== void 0 && value !== null) {
                        return '' + value;
                    } else {
                        return '';
                    }
                });
                // 格式化
                ngModel.$formatters.push(function(value) {
                    return parseFloat(value);
                });
            }
        };
    }).directive('blockLoading', ["YmSpinner", function (spinner) {
        return {
            restrict : "E",
            replace: true,
            scope: {
                imgUrl: '@',
                uiClass: '@', // class样式字符串 <block-loading ui-class="classname" display="loadingShow"></block-loading>
                display: '='  // 父scope指定变量，用于隐藏显示组件 <block-loading ui-class="classname" display="loadingShow"></block-loading>
            },
            template : '<div ng-show="display" class="{{uiClass}}"><img style="width:20px;" src="{{imgUrl}}" alt><span>&nbsp;请稍后...</span></div>',
            link: function (scope, element, attrs, ngModel) {
                // 初始化scope数据
                scope.uiClass = scope.uiClass || 'ym-block-loading';
                scope.imgUrl = scope.imgUrl || spinner.options.loadingImage;
            }
        };
    }]);

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

                    var contentType = config.headers['Content-Type'];
                    if (contentType && global.YmApp.typeOf(contentType) === 'string') {
                        // application/x-www-form-urlencoded
                        if (contentType.indexOf('application/x-www-form-urlencoded') !== -1) {
                            config.data = global.jQuery.param(bodyParams);
                        }

                        // application/json
                        if (contentType.indexOf('application/json') !== -1) {
                            config.data = global.JSON.stringify(bodyParams);
                        }

                        // ...
                    }
                    // 其他`content-type`不用处理，如：undefined、multipart/form-data
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