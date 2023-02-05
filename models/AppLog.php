<?php

namespace app\models;

use Yii;
use app\behaviors\DatetimeBehavior;
use app\builder\common\CommonActiveRecord;

/**
 * This is the model class for table "{{%app_log}}".
 *
 * @property int $id
 * @property string $subject 日志主题，如：订单支付，订单冻结，订单退款
 * @property string $log_level 日志等级：debug、info、warning、error
 * @property string|null $params_content 执行参数
 * @property string|null $result_content 返回结果
 * @property string $created_at 创建时间
 */
class AppLog extends \app\builder\common\CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestampBehavior'] = [
            'class' => DatetimeBehavior::class,
            'attributes' => [
                CommonActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['params_content', 'result_content'], 'string'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['subject'], 'string', 'max' => 50],
            [['log_level'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', '日志主题，如：订单支付，订单冻结，订单退款'),
            'log_level' => Yii::t('app', '日志等级：debug、info、warning、error'),
            'params_content' => Yii::t('app', '执行参数'),
            'result_content' => Yii::t('app', '返回结果'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
