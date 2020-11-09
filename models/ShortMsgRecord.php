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
            'id' => Yii::t('app', 'ID'),
            'service_name' => Yii::t('app', '服务名称'),
            'msg_content' => Yii::t('app', '短信内容'),
            'send_user' => Yii::t('app', '发送人, 0:系统'),
            'receive_mobile' => Yii::t('app', '接收手机号'),
            'send_time' => Yii::t('app', '发送时间'),
        ];
    }
}
