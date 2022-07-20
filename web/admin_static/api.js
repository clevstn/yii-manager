/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 * @author HiLi
 * @since 1.0
 */

!function (win, YmApp) {
    "use strict";

    // 定义[admin]模块api接口
    YmApp.$adminApi = {
        /* ------------- 登录 - 基本验证 ------------- */

        // 检查用户名是否存在
        checkUser: '/admin/site/check-user',
        // 后台用户登录提交
        loginSubmit: '/admin/site/login',

        /* ------------- 登录 - 安全认证 ------------- */
        // 首页
        homeUrl: '/admin/index/index',
        // 登录页
        loginUrl: '/admin/site/login',
        // 发送邮箱验证码/短信验证码
        sendCodeUrl: '/admin/site/send',
        // 安全认证提交
        safeSubmit: '/admin/site/safe-validate'
    };
}(window, window.YmApp);