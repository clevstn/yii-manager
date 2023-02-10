<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user_quick_aciton}}`.
 */
class m230210_084146_create_admin_user_quick_action_table extends Migration
{
    const TABLE_NAME = '{{%admin_user_quick_action}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer()->notNull()->comment('管理员ID'),
            'menu_id' => $this->integer()->notNull()->comment('菜单ID'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ]);

        $this->createIndex('index_admin_id', self::TABLE_NAME, ['admin_id']);
        $this->createIndex('index_menu_id', self::TABLE_NAME, ['menu_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
