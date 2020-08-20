/**
 * this is a background js-file for Yii-Manager
 */
(function (global, jQuery, factory) {
    "use strict"

    if (typeof module === 'object' && typeof module.export === 'object') {
        module.export = factory(global, jQuery);
    } else if (typeof define === 'object' && typeof require === 'object') {
        define('Ym', [], factory(global, jQuery));
    } else {
        global.Ym = factory(global, jQuery);
    }
}(typeof window === void 0 ? this : window, (function (jQuery) {
    return typeof jQuery === void 0 ? require('jquery') : jQuery;
}(window ? window.jQuery : void 0)), function (global, jQuery) {
    // Yii-manager global object
    var Ym;
    /**
     * Ym constructor
     * @constructor
     */
    var YmConstructor = function () {

    };

    /**
     * Sidebar toggles scripts
     */
    YmConstructor.prototype.toggleSideBar = function () {
        jQuery(document).on('click', '[data-toggle="sidebar"]', function (e) {
            var targetElement = jQuery(this).data('target');
            jQuery(targetElement).css('display', 'block');
        });
    };

    Ym = new YmConstructor();
    YmConstructor.prototype.run = function () {
        Ym.toggleSideBar();
    };

    Ym.run();
    return Ym;
}));