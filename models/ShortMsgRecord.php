<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%short_msg_record}}".
 *
 * @property int $id
 * @property string $service_name 服务名称
 * @property string|null $msg_content 短信内容
 * @property int $send_user 发送人, 0:系统
 * @property string $receive_mobile 接收手机号
 * @property string $send_time 发送时间
 */
class ShortMsgRecord extends \app\builder\common\CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%short_msg_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['msg_content'], 'string'],
            [['send_user'], 'integer'],
            [['send_time'], 'required'],
            [['send_time'], 'safe'],
            [['service_name'], 'string', 'max' => 100],
            [['receive_mobile'], 'string', 'max' => 50],
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
            'msg_content' => Yii::t('app', 'message content'),
            'send_user' => Yii::t('app', 'the sender'),
            'receive_mobile' => Yii::t('app', 'receive cell phone number'),
            'send_time' => Yii::t('app', 'the send time'),
        ];
    }
}
