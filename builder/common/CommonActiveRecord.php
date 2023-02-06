<?php
/**
 * 
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\builder\common;

use Yii;
use yii\db\Query;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\behaviors\DatetimeBehavior;

/**
 * 模型继承类
 * @property-read string $error 错误信息
 * @author cleverstone
 * @since ym1.0
 */
class CommonActiveRecord extends ActiveRecord
{
    /**
     * 附加公共行为
     * @return array
     */
    public function behaviors()
    {
        return [
            // 日期处理器
            'timestampBehavior' => [
                'class' => DatetimeBehavior::class,
                'attributes' => [
                    CommonActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    CommonActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * 获取当前模型映射的ActiveQuery对象[调用关联查询，必须声明关联关系，否则附表结果通不过安全检查，字段结果无法显示]
     * 建议：简单查询使用该方法
     * @param array|string $select 查询字段
     * @return ActiveQuery
     */
    public static function activeQuery($select = '*')
    {
        return self::find()->select($select);
    }

    /**
     * 获取Query查询对象[调用关联查询，不用声明关联关系]
     * 建议：关联查询使用该方法
     * @param array|string $select 查询字段
     * @param string 主表别名
     * @return Query
     * @throws
     */
    public static function query($select = '*', $alias = '')
    {
        /* @var Query $query */
        $query = Yii::createObject(Query::className());

        if (empty($alias)) {
            return $query->from(static::tableName())->select($select);
        }

        return $query->from([$alias => static::tableName()])->select($select);
    }

    /**
     * 获取验证错误信息[字符串格式]
     * @return string
     */
    public function getError()
    {
        $firstErrors = $this->firstErrors;
        return reset($firstErrors) ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }
}