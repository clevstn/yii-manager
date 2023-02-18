<?php

use yii\db\Migration;

/**
 * 这个是分布式数据库测试迁移文件,开发时请删除该文件
 * @author cleverstone
 * @since ym1.0
 */
class m200814_071113_create_test_table extends Migration
{
    const TABLE_NAME = '{{%test}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="建表测试"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
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
