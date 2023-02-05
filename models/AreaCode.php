<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%area_code}}".
 * @property int $id
 * @property string $name 地域名称
 * @property string $name_en 英文地域名称
 * @property string $code 电话区号
 * @property int|null $status 状态，0：禁用 1：正常
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 * @author cleverstone
 * @since ym1.0
 */
class AreaCode extends \app\builder\common\CommonActiveRecord
{
    // 状态 - 禁用
    const STATUS_DENY = 0;
    // 状态 - 正常
    const STATUS_NORMAL = 1;

    /**
     * disabled|enabled
     * @var string
     */
    public $action;

    /**
     * 状态集合
     * @return array
     */
    public static function statusMap()
    {
        return [
            self::STATUS_DENY => Yii::t('app.admin', 'disable'),
            self::STATUS_NORMAL => Yii::t('app.admin', 'normal'),
        ];
    }

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
            [['name', 'name_en', 'code', 'status', 'id', 'action'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [0, 1]],
            [['action'], 'in', 'range' => ['disabled', 'enabled']],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'name_en', 'code'], 'string', 'max' => 50],
            [['name', 'name_en'], 'unique', 'on' => ['add']],
            [['name', 'name_en'], 'unique', 'filter' => function ($query) {
                /* @var Query $query */
                $query->andWhere(['<>', 'id', $this->id]);
            }, 'on' => ['edit']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenario = parent::scenarios();
        // 新增
        $scenario['add'] = ['name', 'name_en', 'code'];
        // 编辑
        $scenario['edit'] = ['name', 'name_en', 'code'];
        // 禁用启用
        $scenario['toggle'] = ['id', 'action'];

        return $scenario;
    }

    /**
     * 区号列表
     * @param string|null $status 状态
     * @return array
     */
    public static function areaCodes($status = null)
    {
        $selectColumn = Yii::$app->language === 'zh-CN' ? 'name' : 'name_en AS name';
        return self::find()->filterWhere(['status' => $status])->select($selectColumn)->indexBy('code')->column();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app.admin', 'geographical names'),
            'code' => Yii::t('app.admin', 'telephone area code'),
            'status' => Yii::t('app.admin', 'the status'),
            'created_at' => Yii::t('app.admin', 'the creation time'),
            'updated_at' => Yii::t('app.admin', 'the update time'),
        ];
    }

    /**
     * 获取状态标签
     * @param int $status 状态码
     * @param bool $isHtml 是否是`html`标签
     * @return mixed|string
     */
    public static function statusLabel($status, $isHtml = true)
    {
        if (!$isHtml) {
            return self::statusMap()[$status];
        } else {
            switch ($status) {
                case self::STATUS_NORMAL:  // 正常
                    $label = Yii::t('app.admin', 'normal');
                    return '<span class="label label-success">' . $label . '</span>';
                case self::STATUS_DENY:    // 禁用
                    $label = Yii::t('app.admin', 'disable');
                    return '<span class="label label-danger">' . $label . '</span>';
                default:                   // 未知
                    $label = Yii::t('app.admin', 'unknown');
                    return '<span class="label label-default">' . $label . '</span>';
            }
        }
    }
}
