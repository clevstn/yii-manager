<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%system_config}}`.
 */
class m200814_133439_create_system_config_table extends Migration
{
    const TABLE_NAME = '{{%system_config}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM COMMENT="系统配置"';
        }

        $this->createTable(self::TABLE_NAME, [
            'code' => $this->string(50)->notNull()->comment('代码'),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('名称'),
            'desc' => $this->string()->notNull()->defaultValue('')->comment('描述'),
            'type' => $this->boolean()->notNull()->defaultValue(1)->comment('类型, 1:分组 2:普通配置'),
            'group' => $this->string(50)->notNull()->defaultValue('')->comment('所属分组'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
        ], $tableOptions);

        // 主键
        $this->addPrimaryKey('code', self::TABLE_NAME, ['code']);
        // 类型索引
        $this->createIndex('index_type', self::TABLE_NAME, ['type']);
        // 配置项
        $this->insert(self::TABLE_NAME, [

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
