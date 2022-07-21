/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 * @author cleverstone
 * @since 1.0
 */

// JS语言包,源语言为[[en-US]]
!function (window, YmApp) {
    "use strict";

    // [[zh-CN]]语言包定义
    var zhCNResource = {
        'System Error': '系统错误',
        'Notice': '通知',
        'Please enter email/user name': '请输入邮箱/用户名',
        'Please enter your login password': '请输入登录密码',
        'Be logging in, please wait': '登录认证中,请稍后',
        'The APP code is not supported': 'APP扫码暂不支持',
        'Get mail code': '获取邮件码',
        'Get SMS code': '获取短信码',
        'Please enter the authentication code': '请输入认证码',
        'authenticating, please wait': '认证提交中,请稍后',
    };

    // 获取当前应用语言
    var appLang = YmApp.language();
    // 定义语言转义函数并挂载
    window.YmLang = function (message) {
        switch (appLang) {
            case 'en-US':
                return message;
            case 'zh-CN':
            default:
                return zhCNResource[message] || message;
        }
    };
}(window, window.YmApp);