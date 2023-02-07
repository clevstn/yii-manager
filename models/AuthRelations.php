<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_relations}}".
 *
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
            [['group_id', 'menu_id'], 'required'],
            [['group_id', 'menu_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['group_id', 'menu_id'], 'unique', 'targetAttribute' => ['group_id', 'menu_id']],
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

    /**
     * 获取带有已拥有标记的管理组权限树
     * @param array $systemPers 系统权限数据
     * @param array $ownedPers 已拥有的权限(菜单)ID
     * @param int $id 父ID
     * @return array
     */
    public static function getMarkOwnedPermissionTrees($systemPers, $ownedPers, $id = 0)
    {
        $data = [];
        foreach ($systemPers as $item) {
            if ($item['pid'] == $id) {
                $one = [
                    'title' => $item['label'], // 菜单名称
                    'id' => $item['id'],       // ID
                    'spread' => false,          // 打开树节点
                    'children' => self::getMarkOwnedPermissionTrees($systemPers, $ownedPers, $item['id']), // 子节点数组
                ];
                // 添加图标
                if (!empty($item['icon'])) {
                    $titleStr = '<span style="color:#1E9FFF">';
                    $titleStr .= '<i class="' . $item['icon'] . '"></i>';
                    $titleStr .= '&nbsp;<b>' . $item['label'] . '</b>';
                    $titleStr .= '</span>';
                    $one['title'] = $titleStr;
                }

                // 添加选中状态
                if (in_array($item['id'], $ownedPers)) {
                    if (empty(self::getMarkOwnedPermissionTrees($systemPers, [], $item['id']))) {
                        $one['checked'] = true;
                    }

                    $one['spread'] = true;
                }

                array_push($data, $one);
            }
        }

        return $data;
    }
}
