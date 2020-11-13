<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%area_code}}`.
 */
class m201019_034340_create_area_code_table extends Migration
{
    const TABLE_NAME = '{{%area_code}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="电话区号管理表"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique()->comment('地域名称(中文)'),
            'name_en' => $this->string(100)->notNull()->unique()->comment('地域名称(英文)'),
            'code' => $this->string(50)->notNull()->comment('电话区号'),
            'status' => $this->tinyInteger()->defaultValue(1)->comment('状态，0：禁用 1：正常'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('更新时间'),
        ], $tableOptions);

        $this->insert(self::TABLE_NAME, [
            'name' => '中国',
            'name_en' => 'china',
            'code' => '86',
            'created_at' => now(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%area_code}}');
    }
}
