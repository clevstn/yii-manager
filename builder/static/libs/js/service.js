/**
 * Yii-manager angular service register
 */
!function (global, angular) {
    "use strict";

    var $YmApp = angular.module("$YmApp", []);
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

}(window, window.angular);