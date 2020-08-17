<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%system_config}}`.
 */
class m200814_133439_create_system_config_table extends Migration
{
    const TABLE_NAME = '{{%system_config}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM COMMENT="系统配置"';
        }

        $this->createTable(self::TABLE_NAME, [
            'code' => $this->string(50)->notNull()->comment('代码'),
            'value' => $this->text()->comment('值'),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('名称'),
            'desc' => $this->string()->notNull()->defaultValue('')->comment('字段描述'),
            'tips' => $this->string()->notNull()->defaultValue('')->comment('表单提示'),
            'type' => $this->boolean()->notNull()->defaultValue(1)->comment('类型, 1:分组 2:配置'),
            'group' => $this->string(50)->notNull()->defaultValue('')->comment('所属分组'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
        ], $tableOptions);

        // 主键
        $this->addPrimaryKey('code', self::TABLE_NAME, ['code']);
        // 类型索引
        $this->createIndex('index_type', self::TABLE_NAME, ['type']);
        // 配置项
        $this->batchInsert(self::TABLE_NAME, ['code', 'value', 'name', 'desc', 'tips', 'type', 'group', 'created_at'], array_merge([
            ['WEBSITE_GROUP', '', '网站配置', '网站配置分组', '网站配置分组', 1, '', now()],
            ['ADMIN_GROUP', '', '后台配置', '后台配置分组', '后台配置分组', 1, '', now()],
            ['API_GROUP', '', '接口配置', '接口配置分组', '接口配置分组', 1, '', now()],
            ['UPLOAD_GROUP', '', '上传配置', '上传配置分组', '上传配置分组', 1, '', now()],
            // 网站配置
            ['WEBSITE_SWITCH', '0', '网站维护开关', '0:关闭 1:开启, 当开启后, 网站将显示网站维护标语, 网站将无法使用', '当开启后, 网站将显示网站维护标语, 网站将无法使用', 2, 'WEBSITE_GROUP', now()],
            ['WEBSITE_DENY_TIPS', '网站维护中.我们工程师正在努力抢修,请您耐心等待...', '网站维护标语', '关闭后,网站将禁止访问', '关闭后,网站将禁止访问', 2, 'WEBSITE_GROUP', now()],
            // 后台配置
            ['ADMIN_SSO', '0', '是否开启单点登录', '是否开启后台单点登录, 0:关闭 1:开启', '开启后, 单个账号禁止多人同时登陆', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_CCEE', '1', '是否开启安全认证', '是否开启后台安全认证, 0:关闭 1:邮箱认证 2:短信认证 3:MFA认证', '开启后, 后台登录将进行安全校验', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_ALL', '1', '是否开启尝试登陆限制', '是否开启后台尝试登陆限制, 0:关闭 1开启', '开启后, 登录密码错误次数受限', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_ALL_SIZE', '10', '尝试登陆密码错误次数', '后台尝试登陆密码错误次数, 默认10次', '尝试登陆限制开启后生效', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_ALL_DENY_TIME', '600', '尝试登陆封停时间(秒)', '后台尝试登陆失败封停时间,单位秒', '后台尝试登陆失败封停时间,单位秒', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_OPERATE_LOG', '1', '是否开启操作日志', '是否开启后台操作日志, 0:关闭 1: 开启', '', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_LOGIN_LOG', '1', '是否开启登录日志', '是否开启后台登录日志, 0:关闭 1: 开启', '', 2, 'ADMIN_GROUP', now()],
            ['ADMIN_DEFAULT_PAGE', '20', '分页数量', '后台列表每页数据条数', '后台列表每页数据条数', 2, 'ADMIN_GROUP', now()],
            // 上传配置
            ['UPLOAD_FILE_LIMIT', '0', '上传文件大小', '(非图片)文件上传大小限制单位kb, 0:不限制', '(非图片)文件上传大小限制', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_FILE_EXT', 'md,zip,pdf,xls,xlsx', '允许上传的(非图片)文件扩展名', '允许上传的(非图片)文件扩展名', '允许上传的(非图片)文件扩展名', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_IMAGE_LIMIT', '5120', '上传图片大小', '图片上传大小限制单位kb, 0:不限制', '图片上传大小限制', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_IMAGE_EXT', 'png,jpg,jpeg', '允许上传的图片扩展名', '允许上传的图片扩展名', '允许上传的图片扩展名', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_IMAGE_WMK', '0', '图片是否添加水印', '是否添加水印, 0:否 1:是', '是否添加水印', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_WMK_IMAGE', '0', '水印图片', '水印图片附件ID', '只有开启水印功能才生效', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_WMK_POSITION', 'lt', '水印位置', '水印位置, lt:左上 tm:上中 rt:右上 rm:右中 rb:右下 bm:下中 lb:左下 lm:左中 mm:居中', '只有开启水印功能才生效', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_WMK_OPACITY', '0.2', '透明度', '透明度值: 0.1-1', '透明度值:0.1-0.5', 2, 'UPLOAD_GROUP', now()],
            ['UPLOAD_DRIVER', 'local', '上传驱动', '上传驱动方式, 本地:local 七牛:qiniu 亚马逊s3:s3 阿里对象存储:aliOSS', '', 2, 'UPLOAD_GROUP', now()],
        ], custom_config()));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
