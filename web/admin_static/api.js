/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */

!function (win, YmApp) {
    "use strict";

    // 定义[admin]模块api接口
    YmApp.$adminApi = {
        // 检查用户名是否存在
        checkUser: '/admin/site/check-user',
        // 后台用户登录提交
        loginSubmit: '/admin/site/login',
        // 登录安全认证页面
        loginSafePage: '/admin/site/safe-validate',
    };
}(window, window.YmApp);