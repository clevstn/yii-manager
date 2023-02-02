<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_relations}}".
 *
 * @property int $id
 * @property int $group_id 管理组ID
 * @property int $menu_id 菜单ID
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class AuthRelations extends \app\builder\common\CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return Yii::$app->rbacManager->relationsTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'menu_id', 'created_at'], 'required'],
            [['group_id', 'menu_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group_id' => Yii::t('app', '管理组ID'),
            'menu_id' => Yii::t('app', '菜单ID'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
