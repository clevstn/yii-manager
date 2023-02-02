<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_groups}}".
 *
 * @property int $id
 * @property string $name 角色名
 * @property string $desc 备注
 * @property int $is_enabled 是否开启，0：禁用 1：正常
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class AuthGroups extends \app\builder\common\CommonActiveRecord
{
    // 超管组ID
    const ADMINISTRATOR_GROUP = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return Yii::$app->rbacManager->groupsTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'created_at'], 'required'],
            [['is_enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 250],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '角色名'),
            'desc' => Yii::t('app', '备注'),
            'is_enabled' => Yii::t('app', '是否开启，0：禁用 1：正常'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
