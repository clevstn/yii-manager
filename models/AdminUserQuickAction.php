<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%admin_user_quick_action}}".
 *
 * @property int $id
 * @property int $admin_id 管理员ID
 * @property int $menu_id 菜单ID
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class AdminUserQuickAction extends \app\builder\common\CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_user_quick_action}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'menu_id'], 'required'],
            [['admin_id', 'menu_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['add'] = ['admin_id', 'menu_id'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'menu_id' => Yii::t('app', '菜单ID'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
