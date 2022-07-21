<?php

use yii\db\Migration;

/**
 * 这个是分布式数据库测试迁移文件,开发时请删除该文件
 * @author cleverstone
 * @since 1.0
 */
class m200814_071113_create_test_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%test}}');
    }
}
