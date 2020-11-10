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
            'id' => 'ID',
            'service_name' => Yii::t('app', 'the service name'),
            'email_content' => Yii::t('app', 'email content'),
            'send_user' => Yii::t('app', 'the sender'),
            'receive_email' => Yii::t('app', 'to receive your email'),
            'send_time' => Yii::t('app', 'the send time'),
        ];
    }
}
