<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%system_config}}".
 * @property string $code 代码
 * @property string|null $value 值
 * @property string|null $control 控件类型
 * @property string|null $options 选项
 * @property string $name 名称
 * @property string $desc 字段描述
 * @property string $tips 表单提示
 * @property int $type 类型, 1:分组 2:配置
 * @property string $group 所属分组
 * @property string $created_at 创建时间
 * @property string|null $updated_at 更新时间
 * @author cleverstone
 * @since 1.0
 */
class SystemConfig extends \app\builder\common\CommonActiveRecord
{
    /**
     * 每种控件支持的选项如下:
     * -- text[文本]: 无
     * -- number[数字]: 无
     * -- password[密码]: 无
     * -- time[时间]: 无
     * -- date[日期]: 无
     * -- hidden[隐藏]: 无
     * -- range[区间]: min:1|max:2|default:3|step:1
     * -- file[图片]: 无
     * -- textarea[文本域]: 无
     * -- radio[单选]: 1:开启|2:关闭
     * -- checkbox[多选]: 1:yii|2:yii-manager|3:thinkphp|4:laravel
     * -- select[下拉选择框<单>]: 1:yii|2:yii-manager|3:thinkphp
     * -- multiple[下拉选择框<多>]: 1:yii|2:yii-manager|3:thinkphp
     * -- switch[开关]: 0:开启|1:关闭
     * -- custom[自定义]: class|method
     *
     * 注意: switch选项格式顺序不可改变
     */
    const TEXT = 'text';
    const NUMBER = 'number';
    const PASSWORD = 'password';
    const TIME = 'time';
    const DATE = 'date';
    const HIDDEN = 'hidden';
    const RANGE = 'range';
    const FILE = 'file';
    const TEXTAREA = 'textarea';
    const RADIO = 'radio';
    const CHECKBOX = 'checkbox';
    const SELECT = 'select';
    const MULTIPLE = 'multiple';
    const SW = 'switch';
    const CUSTOM = 'custom';

    /**
     * @var array 已支持的控件类型
     */
    public static $controlMap = [
        self::TEXT,
        self::NUMBER,
        self::PASSWORD,
        self::TIME,
        self::DATE,
        self::HIDDEN,
        self::RANGE,
        self::FILE,
        self::TEXTAREA,
        self::RADIO,
        self::CHECKBOX,
        self::SELECT,
        self::MULTIPLE,
        self::SW,
        self::CUSTOM,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'created_at'], 'required'],
            [['value'], 'string'],
            [['type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'control', 'name', 'group'], 'string', 'max' => 50],
            [['options', 'desc', 'tips'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', '代码'),
            'value' => Yii::t('app', '值'),
            'control' => Yii::t('app', '控件类型'),
            'options' => Yii::t('app', '选项'),
            'name' => Yii::t('app', '名称'),
            'desc' => Yii::t('app', '字段描述'),
            'tips' => Yii::t('app', '表单提示'),
            'type' => Yii::t('app', '类型, 1:分组 2:配置'),
            'group' => Yii::t('app', '所属分组'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
