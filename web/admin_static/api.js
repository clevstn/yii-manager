/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 * @author cleverstone
 * @since ym1.0
 */

!function (win, YmApp) {
    "use strict";

    // 定义[admin]模块api接口
    var api = {
        /* ------------- 登录 - 基本验证 ------------- */

        // 检查用户名是否存在
        checkUser: '/admin/site/check-user',
        // 后台用户登录提交
        loginSubmit: '/admin/site/login',

        /* ------------- 登录 - 安全认证 ------------- */
        // 登录页
        loginUrl: '/admin/site/login',
        // 发送邮箱验证码/短信验证码
        sendCodeUrl: '/admin/site/send',
        // 安全认证提交
        safeSubmit: '/admin/site/safe-validate',

        /* -------------- 首页 ---------------------   */
        homeUrl: '/admin/index/index',
        // 设置快捷项
        indexQuickActionUrl: '/admin/index/quick-setting',
        // 快捷菜单列表
        indexQuickActionListUrl: '/admin/index/quick-list',

        /* -------------- 附件上传 ---------------------   */
        // 上传
        fileUploadUrl: '/admin/upload/add',
        // 简易列表
        attachmentListUrl: '/admin/attachment/list',
        // 移除
        removeAttachmentUrl: '/admin/attachment/remove',
        // 复制
        copyAttachmentUrl: '/admin/attachment/copy',

        /* -------------- 系统配置 ---------------------   */
        // 加载配置项
        loadingConfigItemUrl: '/admin/system-setting/load',

    };

    YmApp.$adminApi = api;
}(window, window.YmApp);