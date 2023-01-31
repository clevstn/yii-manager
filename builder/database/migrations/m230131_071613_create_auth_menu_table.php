<?php

use yii\db\Migration;
use app\components\RbacManager;
use yii\base\InvalidConfigException;

/**
 * 菜单
 * Handles the creation of table `{{%auth_menu}}`.
 * @author cleverstone
 * @since ym1.0
 */
class m230131_071613_create_auth_menu_table extends Migration
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
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="菜单表"';
        }

        $this->createTable($authManager->menuTable, [
            'id' => $this->primaryKey(),
            'label' => $this->string(50)->defaultValue('')->notNull()->comment('菜单名称'),
            'src' => $this->string(250)->unique()->notNull()->comment('源'),
            'link_type' => $this->tinyInteger(2)->defaultValue(1)->notNull()->comment('链接类型：1、路由；2、外链'),
            'icon' => $this->string(50)->defaultValue('')->notNull()->comment('图标，支持fontawesome-v4.0'),
            'dump_way' => $this->string(50)->defaultValue('')->notNull()->comment('跳转方式：_self：内部，_blank：外部'),
            
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable($authManager->menuTable);
    }
}
