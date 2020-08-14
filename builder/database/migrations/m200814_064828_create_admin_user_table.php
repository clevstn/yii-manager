<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user}}`.
 */
class m200814_064828_create_admin_user_table extends Migration
{
    const TABLE_NAME = '{{%admin_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="管理员表"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->unique()->notNull()->comment('管理员用户名'),
            'password' => $this->string()->defaultValue('')->notNull()->comment('密码'),
            'email' => $this->string()->unique()->notNull()->comment('邮箱'),
            'an' => $this->string(10)->defaultValue('86')->notNull()->comment('电话区号'),
            'mobile' => $this->string(20)->defaultValue('')->notNull()->comment('电话号码'),
            'google_key' => $this->string()->defaultValue('')->notNull()->comment('谷歌令牌'),
            'safe_auth' => $this->boolean()->defaultValue(0)->notNull()->comment('是否开启安全认证, 0:不开启 1:邮箱认证 2:短信认证 3:MFA认证'),
            'open_operate_log' => $this->boolean()->defaultValue(0)->notNull()->comment('是否开启操作日志, 0:跟随系统 1:开启 2:关闭'),
            'open_login_log' => $this->boolean()->defaultValue(0)->notNull()->comment('是否开启登录日志, 0:跟随系统 1:开启 2:关闭'),
            'auth_key' => $this->string(100)->defaultValue('')->notNull()->comment('cookie认证密匙'),
            'access_token' => $this->string(100)->defaultValue('')->notNull()->comment('访问令牌'),
            'status' => $this->boolean()->defaultValue(1)->notNull()->comment('账号状态,0:已封停 1:正常'),
            'deny_time' => $this->integer()->defaultValue(0)->notNull()->comment('封停时间'),
            'deny_unit' => $this->boolean()->defaultValue(1)->notNull()->comment('封停单位(对应封停时间), 1:分钟 2:小时 3:天 4:月 5:年'),
            'group' => $this->integer()->defaultValue(0)->notNull()->comment('管理组ID, 0为系统管理员'),
            'photo' => $this->integer()->defaultValue(0)->notNull()->comment('头像(附件ID)'),
            'created_at' => $this->dateTime()->notNull()->comment('注册时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        // 是否开启安全认证索引
        $this->createIndex('index_safe_auth', self::TABLE_NAME, ['safe_auth']);
        // 状态索引
        $this->createIndex('index_status', self::TABLE_NAME, ['status']);
        // 访问令牌索引
        $this->createIndex('index_access_token', self::TABLE_NAME, ['access_token']);
        // 管理组索引
        $this->createIndex('index_group', self::TABLE_NAME, ['group']);
        // 注册时间索引
        $this->createIndex('index_created_at', self::TABLE_NAME, ['created_at']);

        $this->insert(self::TABLE_NAME, [
            'username' => 'super',
            'password' => encrypt_password('super666'),
            'email' => '',
            'auth_key' => random_string(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
