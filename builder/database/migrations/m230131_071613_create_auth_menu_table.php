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
     * @throws \Exception
     */
    public function up()
    {
        $authManager = $this->getAuthManager();
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="菜单表"';
        }

        $this->createTable($authManager->menuTable, [
            'id' => $this->primaryKey(),
            'label' => $this->string(50)->defaultValue('')->notNull()->comment('菜单名称'),
            'icon' => $this->string(50)->defaultValue('')->notNull()->comment('图标，支持fontawesome-v4.0'),
            'label_type' => $this->tinyInteger(2)->defaultValue(1)->notNull()->comment('类型：1、菜单 2、功能'),
            'src' => $this->string(250)->unique()->notNull()->comment('源'),
            'link_type' => $this->tinyInteger(2)->defaultValue(1)->notNull()->comment('链接类型：1、路由；2、外链'),
            'dump_way' => $this->string(50)->defaultValue('')->notNull()->comment('跳转方式：_self：内部，_blank：外部'),
            'desc' => $this->string(250)->defaultValue('')->notNull()->comment('备注'),
            'pid' => $this->integer()->defaultValue(0)->notNull()->comment('父ID'),
            'sort' => $this->integer()->defaultValue(0)->notNull()->comment('排序'),
            'is_quick' => $this->tinyInteger(1)->defaultValue(0)->notNull()->comment('是否允许设置为快捷操作，0：不可以 1：可以；默认：0 注意：快捷操作为get请求，不可动态传参请求，只能打开独立窗口，请根据功能实际情况进行设置。'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('index_label_type', $authManager->menuTable, ['link_type']);
        $this->createIndex('index_pid', $authManager->menuTable, ['pid']);
        $this->createIndex('index_is_quick', $authManager->menuTable, ['is_quick']);

        // 填充数据
        $result = $authManager->updateMenuItems();
        if ($result !== true) {
            throw new \Exception($result);
        }
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
