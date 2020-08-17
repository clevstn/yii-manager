<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/17
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

/**
 * 用户自定义分组或配置项
 * 例如:
 * return [
 *    ['配置项代码', '配置值', '配置名称', '配置描述', '表单提示', '类型, 1:分组 2:配置', '所属分组', '创建时间'],
 * ]
 *
 * @see builder/database/migrations/m200814_133439_create_system_config_table.php
 */
return [
    // 邮箱分组配置
    ['EMAIL_GROUP', '', '邮箱配置', '邮箱配置分组', '邮箱配置分组', 1, '', now()],
    ['SMTP_SERVER', 'smtp.qq.com', 'SMTP服务器', 'SMTP服务器', 'SMTP服务器, 如:smtp.qq.com/smtp.163.com', 2, 'EMAIL_GROUP', now()],
    ['SMTP_PORT', '465', 'SMTP端口', 'SMTP端口', 'SMTP端口,不加密默认25/SSL默认465/TLS默认587', 2, 'EMAIL_GROUP', now()],
    ['SMTP_USER', '', 'SMTP用户名', 'SMTP用户名', 'SMTP用户名', 2, 'EMAIL_GROUP', now()],
    ['SMTP_PASSWORD', '', 'SMTP密码', 'SMTP密码', 'SMTP密码', 2, 'EMAIL_GROUP', now()],
    ['SMTP_SECRET_WAY', '', '加密方式', '加密方式,None:无 SSL:对应默认端口465 TLS:对应默认端口587', '', 2, 'EMAIL_GROUP', now()],
    ['SMTP_SIGN', '', '签名', '发送人签名, 默认使用用户名', '发送人签名, 默认使用用户名', 2, 'EMAIL_GROUP', now()],

];