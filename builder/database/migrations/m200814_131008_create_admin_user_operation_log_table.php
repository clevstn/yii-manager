<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user_operation_log}}`.
 */
class m200814_131008_create_admin_user_operation_log_table extends Migration
{
    const TABLE_NAME = '{{%admin_user_operation_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM COMMENT="管理员操作日志"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'admin_user_id' => $this->integer()->defaultValue(0)->notNull()->comment('管理员ID'),
            'function' => $this->string()->defaultValue('')->notNull()->comment('功能描述,如:新增管理员'),
            'route' => $this->string()->defaultValue('')->notNull()->comment('路由,如:admin/user/add'),
            'ip' => $this->string()->defaultValue('')->notNull()->comment('IP'),
            'operate_status' => $this->boolean()->defaultValue(0)->notNull()->comment('操作状态,0:失败 1:成功'),
            'operate_info' => $this->text()->comment('操作信息'),
            'client_info' => $this->text()->comment('客户端信息'),
            'created_at' => $this->dateTime()->notNull()->comment('操作时间'),
        ], $tableOptions);

        // 管理员ID索引
        $this->createIndex('index_admin_user_id', self::TABLE_NAME, ['admin_user_id']);
        // 操作时间索引
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
