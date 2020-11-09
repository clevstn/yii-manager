<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%email_record}}".
 *
 * @property int $id
 * @property string $service_name 服务名称
 * @property string|null $email_content 邮件内容
 * @property int $send_user 发送人, 0:系统
 * @property string $receive_email 接收邮箱
 * @property string $send_time 发送时间
 */
class EmailRecord extends \app\builder\common\CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email_content'], 'string'],
            [['send_user'], 'integer'],
            [['send_time'], 'required'],
            [['send_time'], 'safe'],
            [['service_name', 'receive_email'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'service_name' => Yii::t('app', '服务名称'),
            'email_content' => Yii::t('app', '邮件内容'),
            'send_user' => Yii::t('app', '发送人, 0:系统'),
            'receive_email' => Yii::t('app', '接收邮箱'),
            'send_time' => Yii::t('app', '发送时间'),
        ];
    }
}
