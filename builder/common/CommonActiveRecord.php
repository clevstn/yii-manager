<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\common;

use yii\db\ActiveRecord;
use app\behaviors\DatetimeBehavior;

/**
 * 模型继承类
 * @property-read string $error 错误信息
 * @author HiLi
 * @since 1.0
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
     * 获取当前模型映射的ActiveQuery对象
     * @param $select
     * @return \yii\db\ActiveQuery
     */
    public static function query($select)
    {
        return self::find()->select($select);
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