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

    const IDENTIFY_TYPE_BASE = 0;  // 基本认证
    const IDENTIFY_TYPE_EMAIL = 1; // 邮箱认证
    const IDENTIFY_TYPE_SMS = 2;   // 短信认证
    const IDENTIFY_TYPE_MFA = 3;   // MFA认证

    const ATTEMPT_SUCCESS = 1;      // 尝试状态:成功
    const ATTEMPT_FAILED = 0;       // 尝试状态:失败

    /**
     * 获取尝试状态标签
     * @param int $attemptStatus 尝试状态
     * @return string
     */
    public static function getAttemptStatusLabel($attemptStatus)
    {
        switch ($attemptStatus) {
            case self::ATTEMPT_SUCCESS:
                return t('login successful', 'app.admin');
            case self::ATTEMPT_FAILED:
                return t('login failed', 'app.admin');
        }

        return '--';
    }

    /**
     * 获取访问认证类型标签
     * @param int $type 认证类型码
     * @return string
     */
    public static function getIdentifyLabel($type)
    {
        switch ($type) {
            case self::IDENTIFY_TYPE_BASE:
                return t('basic authentication', 'app.admin');
            case self::IDENTIFY_TYPE_EMAIL:
                return t('email authentication', 'app.admin');
            case self::IDENTIFY_TYPE_SMS:
                return t('SMS authentication', 'app.admin');
            case self::IDENTIFY_TYPE_MFA:
                return t('OTP authentication', 'app.admin');
            default:
                return t('unknown authentication method', 'app.admin');
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
            'admin_user_id' => Yii::t('app.admin', 'the administrator ID'),
            'identify_type' => Yii::t('app.admin', 'the authentication type'),
            'client_info' => Yii::t('app.admin', 'the client information'),
            'attempt_info' => Yii::t('app.admin', 'try to information'),
            'attempt_status' => Yii::t('app.admin', 'the status'),
            'error_type' => Yii::t('app.admin', 'custom error types'),
            'login_ip' => Yii::t('app.admin', 'the login IP'),
            'created_at' => Yii::t('app.admin', 'the creation time'),
        ];
    }
}
