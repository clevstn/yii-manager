<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user}}`.
 * @author cleverstone
 * @since ym1.0
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
            'photo' => $this->integer()->defaultValue(0)->notNull()->comment('头像(附件ID)'),
            'an' => $this->string(10)->defaultValue('86')->notNull()->comment('电话区号'),
            'mobile' => $this->string(20)->defaultValue('')->notNull()->comment('电话号码'),
            'google_key' => $this->string()->defaultValue('')->notNull()->comment('谷歌令牌'),
            'auth_key' => $this->string(100)->defaultValue('')->notNull()->comment('cookie认证密匙'),
            'access_token' => $this->string(100)->defaultValue('')->notNull()->comment('访问令牌'),
            'status' => $this->boolean()->defaultValue(1)->notNull()->comment('账号状态,0:已封停 1:正常'),
            'deny_end_time' => $this->dateTime()->comment('封停结束时间，null为无限制'),
            'group' => $this->integer()->defaultValue(0)->notNull()->comment('管理组ID, 0为系统管理员'),
            'identify_code' => $this->char(10)->defaultValue('')->notNull()->unique()->comment('身份代码'),
            'pid' => $this->integer()->defaultValue(0)->notNull()->comment('父ID'),
            'path' => $this->text()->comment('关系路径'),
            'created_at' => $this->dateTime()->notNull()->comment('注册时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        // 管理组索引
        $this->createIndex('index_group', self::TABLE_NAME, ['group']);
        // 父ID索引
        $this->createIndex('index_pid', self::TABLE_NAME, ['pid']);
        // 注册时间索引
        $this->createIndex('index_created_at', self::TABLE_NAME, ['created_at']);

        $this->insert(self::TABLE_NAME, [
            'username' => 'root',
            'password' => encrypt_password('root170104'),
            'email' => '',
            'mobile' => '',
            'google_key' => \app\extend\Extend::googleAuth()->createSecret(),
            'auth_key' => random_string(),
            'identify_code' => random_string(true, 10),
            'path' => \app\models\AdminUser::makePath(0, ''),
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
