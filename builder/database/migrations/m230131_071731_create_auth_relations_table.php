<?php

use yii\db\Migration;
use app\components\RbacManager;
use yii\base\InvalidConfigException;

/**
 * Handles the creation of table `{{%auth_relations}}`.
 * @author cleverstone
 * @since ym1.0
 */
class m230131_071731_create_auth_relations_table extends Migration
{
    /**
     * RBAC组件对象
     * @return null|\app\components\RbacManager
     * @throws InvalidConfigException
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->rbacManager;
        if (!$authManager instanceof RbacManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = $this->getAuthManager();
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="权限角色关系表"';
        }

        $this->createTable($authManager->relationsTable, [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull()->comment('管理组ID'),
            'menu_id' => $this->integer()->notNull()->comment('菜单ID'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('index_group_menu', $authManager->relationsTable, ['group_id', 'menu_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable($authManager->relationsTable);
    }
}
