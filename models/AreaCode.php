<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%area_code}}".
 *
 * @property int $id
 * @property string $name 地域名称
 * @property string $code 电话区号
 * @property int|null $status 状态，0：禁用 1：正常
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class AreaCode extends \app\builder\common\CommonActiveRecord
{
    // 状态 - 禁用
    const STATUS_DENY = 0;
    // 状态 - 正常
    const STATUS_NORMAL = 1;

    /**
     * @var array
     */
    public static $statusMap = [
        self::STATUS_DENY => '禁用',
        self::STATUS_NORMAL => '正常',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%area_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'created_at'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'code'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * 区号列表
     * @return array
     * @author cleverstone
     * @since 1.0
     */
    public static function areaCodes()
    {
        return self::find()->select('name')->indexBy('code')->column();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '地域名称',
            'code' => '电话区号',
            'status' => '状态，0：禁用 1：正常',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 获取状态标签
     * @param int $status 状态码
     * @param bool $isHtml 是否是`html`标签
     * @return mixed|string
     * @author cleverstone
     * @since 1.0
     */
    public static function statusLabel($status, $isHtml = true)
    {
        if (!$isHtml) {
            return self::$statusMap[$status];
        } else {
            switch ($status) {
                case self::STATUS_NORMAL:  // 正常
                    return '<span class="label label-success">正常</span>';
                case self::STATUS_DENY:    // 禁用
                    return '<span class="label label-danger">禁用</span>';
                default:                   // 未知
                    return '<span class="label label-default">未知</span>';
            }
        }
    }
}
