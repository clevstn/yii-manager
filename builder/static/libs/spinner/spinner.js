/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

/**
 * this is a background js-file for Yii-Manager
 * if env is windows, the global object is `YmSpinner`
 * @author hili
 * @since 1.0
 */
(function (global, jQuery, factory) {
    "use strict"

    if (typeof module !== "undefined" && module.exports) {
        module.exports = factory(global, jQuery);
    } else if (typeof define === "function" && define.amd) {
        define('YmSpinner', [], factory(global, jQuery));
    } else {
        global.YmSpinner = factory(global, jQuery);
    }
}(typeof window === void 0 ? this : window, (function (jQuery) {
    return typeof jQuery === void 0 ? require('jquery') : jQuery;
}(window ? window.jQuery : void 0)), function (global, jQuery) {

    var YmSpinner;

    /**
     * YmSpinner constructor
     * @constructor
     */
    var YmSpinnerConstructor = function () {
        this.spinnerTree = {}
        this.spinnerIndex = 0
        this.viewContext = jQuery('body')
    };

    /**
     * spinner options
     * @type {{loadingImage: string, timeout: number, containerClass: string, contentTips: [string, string, string]}}
     */
    YmSpinnerConstructor.prototype.options = {
        timeout: 30000,                                     // 最长显示时间
        containerClass: 'ym-spinner-container',             // 容器css类
        innerClass: 'ym-spinner-inner',                     // inner css类
        innerLeftClass: 'ym-spinner-inner-left',            // inner left css类
        innerRightClass: 'ym-spinner-inner-right',          // inner right css类
        loadingImage: 'data:image/gif;base64,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',   // 加载动画
        contentTips: [                                      // 内容提示
            '数据加载中, 请稍后...',
        ],
    };

    /**
     * create template
     * @returns {*|jQuery|HTMLElement}
     */
    YmSpinnerConstructor.prototype.createTemplate = function (tips) {
        var spinnerInnerText = tips ? tips : this.options.contentTips[Math.floor(Math.random() * this.options.contentTips.length)];
        var spinnerContainer = jQuery(document.createElement('div')).addClass('spinner-bounce-in').addClass(this.options.containerClass);
        var spinnerInner = jQuery(document.createElement('div')).addClass('spinner-dialog').addClass(this.options.innerClass);

        var innerLeft = document.createElement('img');
        innerLeft.src = this.options.loadingImage;
        innerLeft.alt = "";
        var spinnerInnerLeft = jQuery(innerLeft).addClass(this.options.innerLeftClass);

        var innerRight = document.createElement('p');
        innerRight.innerText = spinnerInnerText;
        var spinnerInnerRight = jQuery(innerRight).addClass(this.options.innerRightClass);

        return spinnerContainer.html(spinnerInner.html(spinnerInnerLeft).append(spinnerInnerRight));
    };

    /**
     * payload
     */
    YmSpinnerConstructor.prototype.payload = function (currentMask, currentSpinner) {
        var __ = this;

        this.spinnerIndex++;
        var currentIndex = this.spinnerIndex;

        var timeoutIndex = this.options.timeout ? setTimeout(function () {
            __.hide(currentIndex);
        }, this.options.timeout) : null;

        this.spinnerTree[currentIndex] = {
            vm: currentSpinner,
            mask: currentMask,
            timeout: timeoutIndex
        };

        return currentIndex;
    };

    /**
     * show
     * @param tips
     * @returns {YmSpinnerConstructor}
     */
    YmSpinnerConstructor.prototype.show = function (tips) {
        var currentSpinner = this.createTemplate(tips);
        var currentMask = jQuery(document.createElement('div')).addClass('ym-spinner-mask');
        this.viewContext.append(currentMask).append(currentSpinner);

        return this.payload(currentMask, currentSpinner);
    };

    /**
     * hide
     * @returns {YmSpinnerConstructor}
     */
    YmSpinnerConstructor.prototype.hide = function (index) {
        var currentSpinner = this.spinnerTree[index] || null;
        if (currentSpinner) {
            currentSpinner.vm.addClass('spinner-bounce-close');
            currentSpinner.mask.remove();

            var removeDuration = setTimeout(function () {
                clearTimeout(removeDuration);
                currentSpinner.vm.remove();
            }, 300);

            if (currentSpinner.timeout !== null) {
                clearTimeout(currentSpinner.timeout);
            }

            delete this.spinnerTree[index];
        }
    };

    YmSpinner = new YmSpinnerConstructor();
    return YmSpinner;
}));