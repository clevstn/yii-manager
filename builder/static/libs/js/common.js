/**
 * this is a background js-file for Yii-Manager
 * if env is windows, the global object is `YmApp`
 * @author cleverstone
 * @since 1.0
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
     * Init Icheck Plugin
     */
    YmAppConstructor.prototype.initIcheck = function () {
        // Initial icheck options
        jQuery('.icheckbox').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
            increaseArea: '20%' // optional
        });
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
            global.toastr.options.timeOut = 2500; // How long the toast will display without user interaction
            global.toastr.options.extendedTimeOut = 1000; // How long the toast will display after a user hovers over it
            global.toastr.options.showMethod = 'slideDown';
            global.toastr.options.hideMethod = 'fadeOut';
            global.toastr.options.closeMethod = 'fadeOut';
            global.toastr.options.closeDuration = 300;
            // global.toastr.options.positionClass = 'toast-top-center';
        }

        // Init Icheck Plugin
        // note: 这里开启后，angular ng-repeat完成事件时，Icheck的初始化绑定将失效
        // this.initIcheck();
    };

    // Refresh page
    YmAppConstructor.prototype.refresh = function () {
        jQuery(document).on('click', '.ym_script_refresh', function (e) {
            if (global === window) {
                global.self.location.reload();
            } else if (typeof window !== 'undefined') {
                window.self.location.reload();
            }
        });

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