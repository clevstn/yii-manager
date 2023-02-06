<?php

namespace app\models;

use http\Exception\InvalidArgumentException;
use Yii;
use yii\helpers\ArrayHelper;

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
     * 获取菜单类型标签
     * @param int $labelType
     * @return string
     */
    public static function getLabelTypeStr($labelType)
    {
        switch ($labelType) {
            case self::LABEL_TYPE_MENU:
                return html_label('菜单');
            case self::LABEL_TYPE_FUNCTION:
                return html_label('功能', true, 'default');
        }

        throw new InvalidArgumentException(t('parameter error', 'app.admin'));
    }

    /**
     * 获取链接类型标签
     * @param int $linkType
     * @return string
     */
    public static function getLinkTypeStr($linkType)
    {
        switch ($linkType) {
            case self::LINK_TYPE_ROUTE:
                return html_label('路由', true, 'primary');
            case self::LINK_TYPE_URL:
                return html_label('外链', true, 'primary');
        }

        throw new InvalidArgumentException(t('parameter error', 'app.admin'));
    }

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
            [['src'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['label', 'icon', 'dump_way'], 'string', 'max' => 50],
            [['src', 'desc'], 'string', 'max' => 250],
            [['src'], 'unique'],
        ];
    }

    public function scenarios()
    {
        $scenario = parent::scenarios();
        $scenario['edit'] = ['sort', 'desc'];

        return $scenario;
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


    /**
     * 格式化菜单数据
     * @param array $data 数据
     * @param int $id PID
     * @param string $prefix 字符串标识
     * @return array
     */
    public static function getFormatData(array $data, $id = 0, $prefix = '&nbsp;')
    {
        $map = [];
        if ($id != 0) {
            $prefix .= '|------';
        }

        foreach ($data as $value) {
            if ($value['pid'] == $id) {
                $value['_prefix'] = $prefix;
                $map[] = $value;

                $children = self::getFormatData($data, $value['id'], $prefix);
                if (!empty($children)) {
                    $map = array_merge($map, $children);
                }
            }
        }

        return $map;
    }
}
