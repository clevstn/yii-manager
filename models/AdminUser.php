<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\models;

use yii\web\IdentityInterface;
use app\behaviors\PasswordBehavior;
use yii\behaviors\TimestampBehavior;
use app\builder\common\CommonActiveRecord;

/**
 * 后台管理员模型
 * @property integer $id 主键ID
 * @property string $username 用户名
 * @property string $email 邮箱
 * @property string $password 密码
 * @property string $auth_key cookie认证密匙
 * @property string $access_token 访问令牌
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AdminUser extends CommonActiveRecord implements IdentityInterface
{
    /**
     * 设置表格名
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * 附加行为
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();
        // 密码处理器
        $parentBehaviors['passwordBehavior'] = [
            'class' => PasswordBehavior::className(),
            'attributes' => [
                CommonActiveRecord::EVENT_BEFORE_INSERT => 'password',
                CommonActiveRecord::EVENT_BEFORE_UPDATE => 'password',
            ],
        ];

        return $parentBehaviors;
    }

    /**
     * 通过用户ID获取用户
     * @param int|string $id ID
     * @return AdminUser|IdentityInterface|null
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * 通过访问令牌获取用户
     * @param string $token 访问令牌
     * @param null|string $type 授权类型
     * @return AdminUser|IdentityInterface|null
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * 通过用户名获取用户
     * @param string $username 用户名
     * @return AdminUser|null
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}