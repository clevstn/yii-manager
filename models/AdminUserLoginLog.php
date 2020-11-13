<?php

namespace app\models;

use Yii;
use app\behaviors\DatetimeBehavior;
use app\builder\common\CommonActiveRecord;

/**
 * This is the model class for table "{{%admin_user_login_log}}".
 *
 * @property int $id
 * @property int $admin_user_id 管理员ID
 * @property int $identify_type 认证类型, 0:基本认证 1:邮箱认证 2:短信认证 3:MFA认证
 * @property string|null $client_info 客户端信息
 * @property string|null $attempt_info 尝试信息
 * @property int $attempt_status 尝试状态, 0:失败 1:成功
 * @property string $error_type 自定义错误类型
 * @property string $login_ip 登录IP
 * @property string $created_at 创建时间
 */
class AdminUserLoginLog extends \app\builder\common\CommonActiveRecord
{
    // 基本认证
    const IDENTIFY_TYPE_BASE = 0;
    // 邮箱认证
    const IDENTIFY_TYPE_EMAIL = 1;
    // 短信认证
    const IDENTIFY_TYPE_SMS = 2;
    // MFA认证
    const IDENTIFY_TYPE_MFA = 3;

    // 尝试状态:成功
    const ATTEMPT_SUCCESS = 1;
    // 尝试状态:失败
    const ATTEMPT_FAILED = 0;

    /**
     * 获取认证标签
     * @param int $type 认证类型码
     * @return string
     */
    public static function identifyLabel($type)
    {
        switch ($type) {
            case self::IDENTIFY_TYPE_BASE:
                return t('basic authentication');
            case self::IDENTIFY_TYPE_EMAIL:
                return t('email authentication');
            case self::IDENTIFY_TYPE_SMS:
                return t('SMS authentication');
            case self::IDENTIFY_TYPE_MFA:
                return t('OTP authentication');
            default:
                return t('unknown authentication method');
        }
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
    public static function tableName()
    {
        return '{{%admin_user_login_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_user_id', 'identify_type', 'attempt_status'], 'integer'],
            [['client_info', 'attempt_info'], 'string'],
            [['error_type', 'login_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_user_id' => Yii::t('app', 'the administrator ID'),
            'identify_type' => Yii::t('app', 'the authentication type'),
            'client_info' => Yii::t('app', 'the client information'),
            'attempt_info' => Yii::t('app', 'try to information'),
            'attempt_status' => Yii::t('app', 'the status'),
            'error_type' => Yii::t('app', 'custom error types'),
            'login_ip' => Yii::t('app', 'the login IP'),
            'created_at' => Yii::t('app', 'the creation time'),
        ];
    }

    /**
     * 记录登录日志
     * @param array $data 需要记录的数据
     * @return true|string
     */
    public static function in($data)
    {
        $model = new static();
        $model->setAttributes($data);
        $result = $model->save();
        if ($result) {
            return true;
        }

        return $model->error;
    }
}
