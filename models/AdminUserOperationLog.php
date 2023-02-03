<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admin_user_operation_log}}".
 *
 * @property int $id
 * @property int $admin_user_id 管理员ID
 * @property string $function 功能描述,如:新增管理员
 * @property string $route 路由,如:admin/user/add
 * @property string $ip IP
 * @property int $operate_status 操作状态,0:失败 1:成功
 * @property string $operate_info 操作信息
 * @property string|null $client_info 客户端信息
 * @property string $created_at 操作时间
 */
class AdminUserOperationLog extends \app\builder\common\CommonActiveRecord
{
    // operate_status 操作状态,0:失败 1:成功
    const STATUS_OK = 1;
    const STATUS_FAIL = 0;

    /**
     * 获取操作状态标签
     * @param int $status 状态值
     * @return string
     */
    public static function getStatusLabel($status)
    {
        switch ($status) {
            case self::STATUS_OK:
                return t('operate successfully', 'app.admin');
            default:
                return t('operation failure', 'app.admin');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_user_operation_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_user_id', 'operate_status'], 'integer'],
            [['client_info'], 'string'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['function', 'route', 'ip', 'operate_info'], 'string', 'max' => 255],
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
            'function' => Yii::t('app', 'the functional description'),
            'route' => Yii::t('app', 'the routing'),
            'ip' => 'IP',
            'operate_status' => Yii::t('app', 'the operating state'),
            'operate_info' => Yii::t('app', 'the operational information'),
            'client_info' => Yii::t('app', 'the client information'),
            'created_at' => Yii::t('app', 'the operating time'),
        ];
    }
}
