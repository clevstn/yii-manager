<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_menu}}".
 *
 * @property int $id
 * @property string $label 菜单名称
 * @property string $icon 图标，支持fontawesome-v4.0
 * @property int $label_type 类型：1、菜单 2、功能
 * @property string $src 源
 * @property int $link_type 链接类型：1、路由；2、外链
 * @property string $dump_way 跳转方式：_self：内部，_blank：外部
 * @property string $desc 备注
 * @property int $pid 父ID
 * @property int $sort 排序
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 */
class AuthMenu extends \app\builder\common\CommonActiveRecord
{
    // 菜单类型：1、菜单 2、功能
    const LABEL_TYPE_MENU = 1;
    const LABEL_TYPE_FUNCTION = 2;

    // 链接类型：1、路由；2、外链
    const LINK_TYPE_ROUTE = 1;
    const LINK_TYPE_URL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return Yii::$app->rbacManager->menuTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label_type', 'link_type', 'pid', 'sort'], 'integer'],
            [['src', 'created_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['label', 'icon', 'dump_way'], 'string', 'max' => 50],
            [['src', 'desc'], 'string', 'max' => 250],
            [['src'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', '菜单名称'),
            'icon' => Yii::t('app', '图标，支持fontawesome-v4.0'),
            'label_type' => Yii::t('app', '类型：1、菜单 2、功能'),
            'src' => Yii::t('app', '源'),
            'link_type' => Yii::t('app', '链接类型：1、路由；2、外链'),
            'dump_way' => Yii::t('app', '跳转方式：_self：内部，_blank：外部'),
            'desc' => Yii::t('app', '备注'),
            'pid' => Yii::t('app', '父ID'),
            'sort' => Yii::t('app', '排序'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
