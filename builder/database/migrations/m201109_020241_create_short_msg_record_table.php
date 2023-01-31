<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%short_msg_record}}`.
 */
class m201109_020241_create_short_msg_record_table extends Migration
{
    const TABLE_NAME = '{{%short_msg_record}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="短信记录表"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'service_name' => $this->string(100)->notNull()->defaultValue('')->comment('服务名称'),
            'msg_content' => $this->text()->comment('短信内容'),
            'auth_code' => $this->string(100)->notNull()->defaultValue('')->comment('认证码'),
            'send_user' => $this->bigInteger()->notNull()->defaultValue(0)->comment('发送人, 0:系统'),
            'receive_mobile' => $this->string(50)->notNull()->defaultValue('')->comment('接收手机号'),
            'send_time' => $this->dateTime()->notNull()->comment('发送时间'),
        ], $tableOptions);

        // 复合索引，【接收手机号、认证码】
        $this->createIndex('index_complex_mobile_code', self::TABLE_NAME, ['receive_mobile', 'auth_code']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
