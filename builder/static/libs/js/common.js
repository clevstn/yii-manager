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

    /**
     * 字符串首字母大写
     * @param str
     * @return {string}
     */
    YmAppConstructor.prototype.firstUpperCase = function (str) {
        return str.toString()[0].toUpperCase() + str.toString().slice(1);
    };

    /**
     * 小驼峰转下划线
     * @param str
     * @param delimiter 分隔符
     * @return {string}
     */
    YmAppConstructor.prototype.camelcaseToHyphen = function (str, delimiter) {
        delimiter = delimiter || '_';
        return (str.toString()[0].toLowerCase() + str.toString().slice(1)).replace(/([A-Z])/g, `${delimiter}$1`).toLowerCase();
    };

    /**
     * 判断数组或对象中是否存在指定值
     * @param value
     * @param validList
     * @return {boolean}
     */
    YmAppConstructor.prototype.oneOf = function (value, validList) {
        var varType = Object.prototype.toString.call(validList);
        if (varType === '[object Object]') {
            for (var key in validList) {
                if (validList[key] === value) {
                    return true;
                }
            }

            return false;
        } else if (varType === '[object Array]') {
            for (var i = 0; i < validList.length; i++) {
                if (value === validList[i]) {
                    return true;
                }
            }

            return false;
        } else {
            throw 'Argument [validList] type error.';
        }
    };

    /**
     * clone数据
     * @param _deepCopy
     * @return {*}
     */
    YmAppConstructor.prototype.deepCopy = function (_deepCopy) {
        function deepCopy() {
            return _deepCopy.apply(this, arguments);
        }

        deepCopy.toString = function () {
            return _deepCopy.toString();
        };

        return deepCopy;
    }(function (data) {
        var t = this.typeOf(data);
        var o = void 0;

        if (t === 'array') {
            o = [];
        } else if (t === 'object') {
            o = {};
        } else {
            return data;
        }

        if (t === 'array') {
            for (var i = 0; i < data.length; i++) {
                o.push(this.deepCopy(data[i]));
            }
        } else if (t === 'object') {
            for (var _i in data) {
                o[_i] = this.deepCopy(data[_i]);
            }
        }

        return o;
    });

    /**
     * 向上取整保留n位
     * @param n
     * @param precision
     * @returns {number}
     */
    YmAppConstructor.prototype.xceil = function (n, precision) {
        precision = precision || 2;
        var multiplier = Math.pow(10, precision);

        return Math.ceil(this.numberMul(n, multiplier)) / multiplier;
    };

    /**
     * 向下取整保留n位
     * @param n
     * @param precision
     * @returns {number}
     */
    YmAppConstructor.prototype.xfloor = function (n, precision) {
        precision = precision || 2;
        var multiplier = Math.pow(10, precision);

        return Math.floor(this.numberMul(n, multiplier)) / multiplier;
    };

    /**
     * 四舍五入取整保留n位
     * @param n
     * @param precision
     * @returns {number}
     */
    YmAppConstructor.prototype.xround = function (n, precision) {
        precision = precision || 2;
        var multiplier = Math.pow(10, precision);
        return Math.round(this.numberMul(n, multiplier)) / multiplier;
    };

    /**
     * 高精度加法
     * @param n1
     * @param n2
     * @return {number}
     */
    YmAppConstructor.prototype.numberAdd = function (n1, n2) {
        var r1 = void 0,
            r2 = void 0,
            m = void 0;
        try {
            r1 = n1.toString().split('.')[1].length;
        } catch (e) {
            r1 = 0;
        }

        try {
            r2 = n2.toString().split('.')[1].length;
        } catch (e) {
            r2 = 0;
        }

        m = Math.pow(10, Math.max(r1, r2));
        var a = parseInt(n1 * m);
        var b = parseInt(n2 * m);

        return (a + b) / m;
    };

    /**
     * 高精度减法
     * @param n1
     * @param n2
     * @return {*|number}
     */
      YmAppConstructor.prototype.numberSub = function (n1, n2) {
        return this.numberAdd(n1, -n2);
    };

    /**
     * 高精度乘法
     * @param n1
     * @param n2
     * @return {number}
     */
    YmAppConstructor.prototype.numberMul = function (n1, n2) {
        var m = 0,
            s1 = n1.toString(),
            s2 = n2.toString();
        try {
            m += s1.split('.')[1].length;
        } catch (e) {}

        try {
            // m是累加结果
            m += s2.split('.')[1].length;
        } catch (e) {}

        return Number(s1.replace('.', '')) * Number(s2.replace('.', '')) / Math.pow(10, m);
    };

    /**
     * 高精度除法
     * @param n1
     * @param n2
     * @return {number}
     */
    YmAppConstructor.prototype.numberDiv = function (n1, n2) {
        var r1 = void 0,
            r2 = void 0,
            m = void 0;
        try {
            r1 = n1.toString().split('.')[1].length;
        } catch (e) {
            r1 = 0;
        }

        try {
            r2 = n2.toString().split('.')[1].length;
        } catch (e) {
            r2 = 0;
        }

        m = Math.pow(10, Math.max(r1, r2));
        var a = parseInt(n1 * m);
        var b = parseInt(n2 * m);

        return a / b;
    };

    /**
     * 数据单位转换
     * @param number
     * @return {*}
     */
    YmAppConstructor.prototype.unitInto = function (number) {
        if (!number) return '--';
        var value = isNaN(Number(number)) ? 0 : Number(number);

        var z = 10000,  // 万
            y = 100000000,  // 亿
            q = 100000000000, // 千亿
            w = 1000000000000; // 万亿
        if (value >= z && value < y) {
            value = this.xfloor(this.numberDiv(value, z)) + '万';
        } else if (value >= y && value < q) {
            value = this.xfloor(this.numberDiv(value, y)) + '亿';
        } else if (value >= q && value < w) {
            value = this.xfloor(this.numberDiv(value, q)) + '千亿';
        } else if (value >= w) {
            value = this.xfloor(this.numberDiv(value, w)) + '万亿';
        }

        return value;
    };

    /**
     * 滚动到顶部(动画效果)
     * @param el
     * @param from
     * @param to
     * @param duration
     * @param endCallback
     */
    YmAppConstructor.prototype.scrollTop = function (el) {
        var from = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
        var to = arguments[2];
        var duration = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 500;
        var endCallback = arguments[4];

        if (!window.requestAnimationFrame) {
            window.requestAnimationFrame = window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
                return window.setTimeout(callback, 1000 / 60);
            };
        }
        var difference = Math.abs(from - to);
        var step = Math.ceil(difference / duration * 50);

        function scroll(start, end, step) {
            if (start === end) {
                endCallback && endCallback();
                return;
            }

            var d = start + step > end ? end : start + step;
            if (start > end) {
                d = start - step < end ? end : start - step;
            }

            if (el === window) {
                window.scrollTo(d, d);
            } else {
                el.scrollTop = d;
            }

            window.requestAnimationFrame(function () {
                return scroll(d, end, step);
            });
        }

        scroll(from, to, step);
    };

    /**
     * 解析js脚本
     * @param script
     * @return {never}
     */
    YmAppConstructor.prototype.evalScript = function (script) {
        var fn = new Function("return " + script);

        return fn();
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