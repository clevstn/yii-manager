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
            'id' => Yii::t('app', 'ID'),
            'admin_user_id' => Yii::t('app', '管理员ID'),
            'function' => Yii::t('app', '功能描述,如:新增管理员'),
            'route' => Yii::t('app', '路由,如:admin/user/add'),
            'ip' => Yii::t('app', 'IP'),
            'operate_status' => Yii::t('app', '操作状态,0:失败 1:成功'),
            'operate_info' => Yii::t('app', '操作信息'),
            'client_info' => Yii::t('app', '客户端信息'),
            'created_at' => Yii::t('app', '操作时间'),
        ];
    }
}
