!function (window, angular) {
    "use strict";
    var _easyApp = angular.module("EasyApp", ["YmAppModule"]);
    _easyApp.controller('indexCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer) {
        console.log('首页')
    }]);
}(window, window.angular);