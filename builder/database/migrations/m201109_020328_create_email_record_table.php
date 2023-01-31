<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%email_record}}`.
 */
class m201109_020328_create_email_record_table extends Migration
{
    const TABLE_NAME = '{{%email_record}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="邮件记录表"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'service_name' => $this->string(100)->notNull()->defaultValue('')->comment('服务名称'),
            'email_content' => $this->text()->comment('邮件内容'),
            'auth_code' => $this->string(100)->notNull()->defaultValue('')->comment('认证码'),
            'send_user' => $this->bigInteger()->notNull()->defaultValue(0)->comment('发送人, 0:系统'),
            'receive_email' => $this->string(100)->notNull()->defaultValue('')->comment('接收邮箱'),
            'send_time' => $this->dateTime()->notNull()->comment('发送时间'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
