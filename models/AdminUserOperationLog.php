<?php

namespace app\models;

use Yii;
use app\behaviors\DatetimeBehavior;
use app\builder\common\CommonActiveRecord;

/**
 * This is the model class for table "{{%admin_user_operation_log}}".
 *
 * @property int $id
 * @property int $admin_user_id 管理员ID
 * @property string $function 功能描述,如:新增管理员
 * @property string $route 路由,如:admin/user/add
 * @property string $ip IP
 * @property int $operate_status 操作状态,0:失败 1:成功
 * @property string|null $operate_info 操作信息
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
     * @param boolean $toHtml 是否转html
     * @return string
     */
    public static function getStatusLabel($status, $toHtml = true)
    {
        switch ($status) {
            case self::STATUS_OK:
                return html_label(t('operate successfully', 'app.admin'), $toHtml);
            case self::STATUS_FAIL:
                return html_label(t('operation failure', 'app.admin'), $toHtml, 'default');
        }

        return '--';
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
        return '{{%admin_user_operation_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_user_id', 'operate_status'], 'integer'],
            [['client_info', 'operate_info'], 'string'],
            [['function', 'route', 'ip'], 'string', 'max' => 255],
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
            'function' => Yii::t('app.admin', 'the functional description'),
            'route' => Yii::t('app.admin', 'the routing'),
            'ip' => 'IP',
            'operate_status' => Yii::t('app.admin', 'the operating state'),
            'operate_info' => Yii::t('app.admin', 'the operational information'),
            'client_info' => Yii::t('app.admin', 'the client information'),
            'created_at' => Yii::t('app.admin', 'the operating time'),
        ];
    }

    /**
     * 关联查询`admin_user`表定义
     * @return \yii\db\ActiveQuery
     * - hasOne参数
     * 1：附表模型
     * 2：key：附表ID  value：主表ID
     */
    /*public function getAdminUserModel()
    {
        return $this->hasOne(AdminUser::class, ['id' => 'admin_user_id']);
    }*/
}
