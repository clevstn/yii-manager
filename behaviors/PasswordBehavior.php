<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\behaviors;

use yii\db\BaseActiveRecord;
use yii\base\InvalidCallException;
use yii\behaviors\AttributeBehavior;

/**
 * 密码处理器
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class PasswordBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive timestamp value
     * Set this property to false if you do not want to record the creation time.
     */
    public $passwordAttribute = 'password';

    /**
     * @var int Default cost used for password hashing.
     * Allowed value is between 4 and 31.
     * @see generatePasswordHash()
     * @since 1.0
     */
    public $passwordHashCost = 4;

    /**
     * {@inheritdoc}
     *
     * In case, when the value is `null`, the result of the PHP function [time()](https://secure.php.net/manual/en/function.time.php)
     * will be used as value.
     */
    public $value;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->passwordAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->passwordAttribute,
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](https://secure.php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $attribute = $this->passwordAttribute;
            $value = $this->owner->$attribute;
            if (empty($value)) {
                return '';
            }

            return $this->encryptPassword($value);
        }

        return parent::getValue($event);
    }

    /**
     * 加密密码
     * @param string $value 明文密码
     * @return string
     * @throws \yii\base\Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function encryptPassword($value)
    {
        return \Yii::$app->security->generatePasswordHash($value, $this->passwordHashCost);
    }

    /**
     * Updates a timestamp attribute to the current timestamp.
     *
     * ```php
     * $model->touch('lastVisit', $password);
     * ```
     * @param string $attribute the name of the attribute to update.
     * @param string $password 明文密码
     * @throws InvalidCallException if owner is a new record (since version 2.0.6).
     * @throws \yii\base\Exception
     */
    public function touch($attribute, $password)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the password is not possible on a new record.');
        }

        $owner->updateAttributes(array_fill_keys((array)$attribute, $this->encryptPassword($password)));
    }
}