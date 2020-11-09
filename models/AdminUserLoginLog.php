<?php

namespace app\models;

use Yii;

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
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['error_type', 'login_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'admin_user_id' => Yii::t('app', '管理员ID'),
            'identify_type' => Yii::t('app', '认证类型, 0:基本认证 1:邮箱认证 2:短信认证 3:MFA认证'),
            'client_info' => Yii::t('app', '客户端信息'),
            'attempt_info' => Yii::t('app', '尝试信息'),
            'attempt_status' => Yii::t('app', '尝试状态, 0:失败 1:成功'),
            'error_type' => Yii::t('app', '自定义错误类型'),
            'login_ip' => Yii::t('app', '登录IP'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
