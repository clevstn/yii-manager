/**
 * 
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

/**
 * this is a background js-file for Yii-Manager
 * if the env is windows, the global object is `YmApp`
 * @author cleverstone
 * @since ym1.0
 */
(function (global, jQuery, factory) {
    "use strict"

    if (typeof module !== "undefined" && module.exports) {
        module.exports = factory(global, jQuery);
    } else if (typeof define === "function" && define.amd) {
        define('YmApp', [], factory(global, jQuery));
    } else {
        global.YmApp = factory(global, jQuery);
    }
}(typeof window === void 0 ? this : window, (function (jQuery) {
    return typeof jQuery === void 0 ? require('jquery') : jQuery;
}(window ? window.jQuery : void 0)), function (global, jQuery) {
    // Yii-manager global object
    var YmApp;
    /**
     * YmApp constructor
     * @constructor
     */
    var YmAppConstructor = function () {

    };

    /**
     * Sidebar toggles scripts
     */
    YmAppConstructor.prototype.toggleSideBar = function () {
        jQuery(document).on('click', '[data-toggle="sidebar"]', function (e) {
            var targetElement = jQuery(this).data('target'),
                jQueryElement = jQuery(targetElement);
            if (jQueryElement.hasClass('ym-show')) {
                jQueryElement.removeClass('ym-show');
            } else {
                jQueryElement.addClass('ym-show');
            }

        });
    };

    /**
     * Init table Icheck
     */
    YmAppConstructor.prototype.initTableIcheck = function () {
        // Initial icheck options
        jQuery('.tableCheckbox,.tableCheckboxTool').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

        jQuery(".tableCheckboxTool").on("ifClicked", function (e) {
            if (e.target.checked) {
                // All unchecked
                jQuery(".tableCheckbox").iCheck("uncheck");
            } else {
                // All checked
                jQuery(".tableCheckbox").iCheck("check");
            }
        });

        // Cancel all checked or submit all checked
        jQuery(".tableCheckbox").on("ifClicked", function (e) {
            var checkedCount = 0;
            var unCheckedCount = 0;

            var jqTableCheckbox = jQuery(".tableCheckbox");
            jqTableCheckbox.each(function () {
                if (this.checked) {
                    checkedCount++;
                } else {
                    unCheckedCount++;
                }
            });

            if (e.target.checked) {
                checkedCount--;
                unCheckedCount++;
            } else {
                unCheckedCount--;
                checkedCount++;
            }

            if (jqTableCheckbox.length === checkedCount) {
                jQuery(".tableCheckboxTool").iCheck("check");
            } else {
                jQuery(".tableCheckboxTool").iCheck("uncheck");
            }
        });

    };

    /**
     * Uncheck table icheck checked status
     */
    YmAppConstructor.prototype.uncheckTableIcheck = function () {
        jQuery('.tableCheckbox,.tableCheckboxTool').iCheck("uncheck");
    };

    /**
     * Get the table items for checked
     */
    YmAppConstructor.prototype.getTableCheckedData = function () {
        var dataMap = [];
        jQuery(".tableCheckbox").each(function () {
            var flag = jQuery(this).is(":checked");
            if (flag) {
                var thisVal = jQuery(this).val();
                dataMap.push(jQuery.parseJSON(thisVal));
            }
        });

        return dataMap;
    };

    /**
     * Init all plugins
     */
    YmAppConstructor.prototype.initAllPlugins = function () {
        if (typeof jQuery.fn !== 'undefined' && jQuery.fn.select2) {
            /* Sets select2 bootstrap3-theme */
            jQuery.fn.select2.defaults.set("theme", "bootstrap");
        }

        if (typeof global.toastr !== 'undefined' && global.toastr.options) {
            /* Initial toastr options */
            global.toastr.options.closeButton = true;
            global.toastr.options.progressBar = true;
            global.toastr.options.timeOut = 5000; // How long the toast will display without user interaction
            global.toastr.options.extendedTimeOut = 2000; // How long the toast will display after a user hovers over it
            global.toastr.options.showMethod = 'slideDown';
            global.toastr.options.hideMethod = 'fadeOut';
            global.toastr.options.closeMethod = 'fadeOut';
            global.toastr.options.closeDuration = 300;
            // global.toastr.options.positionClass = 'toast-top-center';
        }

        // Initial public icheck options
        jQuery('.icheck-control').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });

    };

    // Refresh page
    YmAppConstructor.prototype.refresh = function () {
        jQuery(document).on('click', '#ym_script_refresh', function (e) {
            if (global === window) {
                global.self.location.reload();
            } else if (typeof window !== 'undefined') {
                window.self.location.reload();
            }
        });

    };

    /**
     * type of
     * @param obj
     * @returns {*}
     */
    YmAppConstructor.prototype.typeOf = function (obj) {
        var toString = Object.prototype.toString;
        var map = {
            '[object Boolean]': 'boolean',
            '[object Number]': 'number',
            '[object String]': 'string',
            '[object Function]': 'function',
            '[object Array]': 'array',
            '[object Date]': 'date',
            '[object RegExp]': 'regExp',
            '[object Undefined]': 'undefined',
            '[object Null]': 'null',
            '[object Object]': 'object'
        };
        return map[toString.call(obj)];
    };

    /**
     * 获取对象的keys
     * @param obj
     * @returns {[]}
     */
    YmAppConstructor.prototype.keys = function (obj) {
        var keys = [];
        for (var i in obj) {
            keys.push(i);
        }

        return keys;
    };

    /**
     * 获取当前应用语言
     * @return string
     */
    YmAppConstructor.prototype.language = function () {
        return jQuery('html').attr('lang') || 'zh-CN';
    };

    /**
     * 获取当前时间戳
     * @returns {number}
     */
    YmAppConstructor.prototype.getTime = function () {
        var DateObj = new Date();
        return Math.floor(DateObj.getTime() / 1000);
    };

    // Run
    YmAppConstructor.prototype.run = function () {
        this.toggleSideBar();
        this.initAllPlugins();
        this.refresh();
    };

    YmApp = new YmAppConstructor();
    YmApp.run();

    return YmApp;

}));