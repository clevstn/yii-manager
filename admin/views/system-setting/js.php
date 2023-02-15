<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */
/* @var array $config  配置项：group => [code => ...]  */
/* @var array $group  分组项 */
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
        _EasyApp.controller('_EasyApp_SystemConfigCtrl', ["$scope", "$http", "$timeout", "$interval", "$rootScope", "YmApp", "toastr", "jQuery", "yii", "YmSpinner", "Swal", "laydate", "layer", "layui", function ($scope, $http, $timeout, $interval, $rootScope, YmApp, toastr, jQuery, yii, YmSpinner, Swal, laydate, layer, layui) {
            // 初始化
            var initAll = function () {
                mountedLaydate(); // 挂载日期插件
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

            // 初始化
            initAll();
        }]);
    }(window, window._EasyApp);
</script>

