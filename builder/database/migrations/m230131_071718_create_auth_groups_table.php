<?php

use yii\db\Migration;
use app\components\RbacManager;
use yii\base\InvalidConfigException;

/**
 * Handles the creation of table `{{%auth_groups}}`.
 */
class m230131_071718_create_auth_groups_table extends Migration
{
    /**
     * RBAC组件对象
     * @return null|\app\components\RbacManager;
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
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="管理组表"';
        }

        $this->createTable($authManager->groupsTable, [
            'id' => $this->primaryKey(),

        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable($authManager->groupsTable);
    }
}
