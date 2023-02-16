<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachment}}`.
 */
class m230212_022058_create_attachment_table extends Migration
{
    const TABLE_NAME = '{{%attachment}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'origin_name' => $this->string(255)->notNull()->defaultValue('')->comment('文件原名称'),
            'type' => $this->string(100)->notNull()->defaultValue('未分类')->comment('文件分类，分类名称可按后台菜单名称命名。'),
            'bucket' => $this->string(50)->notNull()->comment('存储空间'),
            'save_directory' => $this->string(50)->notNull()->comment('保存目录'),
            'path_prefix' => $this->string(50)->notNull()->comment('路径前缀'),
            'basename' => $this->string(255)->notNull()->comment('文件名'),
            'size' => $this->integer()->notNull()->defaultValue(0)->comment('文件大小(字节)'),
            'ext' => $this->string(20)->notNull()->defaultValue('')->comment('扩展名'),
            'mime' => $this->string(50)->notNull()->defaultValue('')->comment('文件类型'),
            'hash' => $this->string(255)->notNull()->defaultValue('')->comment('文件hash值'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ]);

        $this->createIndex('index_bucket', self::TABLE_NAME, ['bucket']);
        $this->createIndex('index_save_directory', self::TABLE_NAME, ['save_directory']);
        $this->createIndex('index_path_prefix', self::TABLE_NAME, ['path_prefix']);
        $this->createIndex('index_basename', self::TABLE_NAME, ['basename']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
