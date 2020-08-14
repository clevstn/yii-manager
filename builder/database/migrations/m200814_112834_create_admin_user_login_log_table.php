<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user_login_log}}`.
 */
class m200814_112834_create_admin_user_login_log_table extends Migration
{
    const TABLE_NAME = '{{%admin_user_login_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM COMMENT="管理员登录日志"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'admin_user_id' => $this->integer()->defaultValue(0)->notNull()->comment('管理员ID'),
            'safe_way' => $this->boolean()->defaultValue(0)->notNull()->comment('安全认证, 0:无 1:邮箱认证 2:短信认证 3:MFA认证'),
            'client_info' => $this->text()->comment('客户端信息'),
            'attempt_info' => $this->text()->comment('尝试信息'),
            'attempt_status' => $this->boolean()->defaultValue(0)->notNull()->comment('尝试状态, 0:失败 1:成功'),
            'error_type' => $this->string()->defaultValue('')->notNull()->comment('自定义错误类型'),
            'login_ip' => $this->string()->defaultValue('')->notNull()->comment('登录IP'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
        ], $tableOptions);
        // 管理员ID索引
        $this->createIndex('index_admin_user_id', self::TABLE_NAME, ['admin_user_id']);
        // 创建时间索引
        $this->createIndex('index_created_at', self::TABLE_NAME, ['created_at']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
