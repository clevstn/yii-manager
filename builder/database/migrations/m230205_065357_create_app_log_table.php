<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%app_log}}`.
 */
class m230205_065357_create_app_log_table extends Migration
{
    const TABLE_NAME = '{{%app_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM COMMENT="应用日志（即端业务日志）"';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'subject' => $this->string(50)->notNull()->defaultValue('')->comment('日志主题，如：订单支付，订单冻结，订单退款'),
            'log_level' => $this->char(30)->notNull()->defaultValue('')->comment('日志等级：debug、info、warning、error'),
            'params_content' => $this->text()->comment('执行参数'),
            'result_content' => $this->text()->comment('返回结果'),
            'created_at' => $this->dateTime()->notNull()->comment('创建时间'),
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
