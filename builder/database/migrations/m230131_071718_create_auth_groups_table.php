<?php

use yii\db\Migration;
use app\components\RbacManager;
use yii\base\InvalidConfigException;

/**
 * Handles the creation of table `{{%auth_groups}}`.
 * @author cleverstone
 * @since ym1.0
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
            'name' => $this->string(50)->unique()->notNull()->comment('角色名'),
            'desc' => $this->string(250)->defaultValue('')->notNull()->comment('备注'),
            'is_enabled' => $this->tinyInteger(1)->defaultValue(1)->notNull()->comment('是否开启，0：禁用 1：正常'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('index_is_enabled', $authManager->groupsTable, ['is_enabled']);
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
