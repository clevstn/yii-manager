<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\models;

use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;
use app\behaviors\PasswordBehavior;
use app\builder\common\CommonActiveRecord;
use app\models\AdminUserLoginLog as LoginLog;

/**
 * 后台管理员
 * @property integer $id 主键ID
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $email 邮箱
 * @property int $photo 头像(附件ID)
 * @property string $an 电话区号
 * @property string $mobile 电话号码
 * @property string $google_key 谷歌令牌
 * @property string $auth_key cookie认证密匙
 * @property string $access_token 访问令牌
 * @property int $status 账号状态,0:已封停 1:正常
 * @property int $deny_end_time 封停结束时间，null为无限制
 * @property int $group 管理组ID, 0为系统管理员
 * @property string $identify_code 身份代码
 * @property int $pid 父ID
 * @property string $path 关系路径
 * @property string $created_at 注册时间
 * @property string $updated_at 更新时间
 * @property-read string $authKey cookie认证密匙
 * @author cleverstone
 * @since ym1.0
 */
class AdminUser extends CommonActiveRecord implements IdentityInterface
{

    const PATH_SPLIT_SYMBOL = '-'; // 路由分割符号

    const STATUS_DENY = 0;   // 禁用
    const STATUS_NORMAL = 1; // 正常

    const SAFE_AUTH_CLOSE = 0;   // 关闭安全认证
    const SAFE_AUTH_EMAIL = 1;   // 邮箱认证
    const SAFE_AUTH_MESSAGE = 2; // 短信认证
    const SAFE_AUTH_OTP = 3;     // MFA认证

    /**
     * @var string 我的上级
     */
    public $parent;

    /**
     * @var string 重复密码
     */
    public $repassword;

    /**
     * @var string 操作项
     * - disabled 封停
     * - enabled  解封
     */
    public $action;

    /**
     * @var string 用户名或邮箱
     */
    public $usernameOrEmail;

    /**
     * 定义表格名
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * 获取状态标签
     * @param int $status 状态
     * @param boolean $isHtml 是否是html
     * @return string
     */
    public static function getStatusLabel($status, $isHtml = false)
    {
        $map = [
            self::STATUS_DENY => Yii::t('app.admin', 'closure'),
            self::STATUS_NORMAL => Yii::t('app.admin', 'normal'),
        ];
        if (isset($map[$status])) {
            return html_label(Yii::t('app.admin', $map[$status]), $isHtml, $status == self::STATUS_DENY ? 'danger' : 'success');
        }

        return html_label(Yii::t('app.admin', 'unknown'), $isHtml, 'default');
    }

    /**
     * 获取安全认证标签
     * @param int $safeAuth 是否开启安全认证标识值
     * @return string
     */
    public static function safeAuthLabelMap($safeAuth)
    {
        $map = [
            self::SAFE_AUTH_CLOSE => Yii::t('app.admin', 'close'),
            self::SAFE_AUTH_EMAIL => Yii::t('app.admin', 'email authentication'),
            self::SAFE_AUTH_MESSAGE => Yii::t('app.admin', 'SMS authentication'),
            self::SAFE_AUTH_OTP => Yii::t('app.admin', 'OTP authentication'),
        ];

        if (isset($map[$safeAuth])) {
            return $map[$safeAuth];
        }

        return Yii::t('app.admin', 'unknown');
    }

    /**
     * 通过用户`id`获取模型实例
     * @param int|string $id id
     * @return AdminUser|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * 通过访问令牌获取模型实例
     * @param string $token 访问令牌
     * @param null|string $type 授权类型
     * @return AdminUser|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * 通过用户名获取模型实例
     * @param string $username 用户名
     * @return AdminUser|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * 组成[[path]]
     * @param int $pid 父级`id`
     * @param string $parentPath 父级`path`
     * @return string
     */
    public static function makePath($pid, $parentPath)
    {
        return $parentPath . $pid . self::PATH_SPLIT_SYMBOL;
    }

    /**
     * 是否封停中
     * @param int $adminId 管理员ID
     * @param null|string $expireDate 封停过期时间
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function isDisabled($adminId, $expireDate = null)
    {
        if (empty($expireDate)) {
            return true;
        } else {
            $expireTime = strtotime($expireDate);
            if ($expireTime && $expireTime > time()) {
                return true;
            } else {
                /* 封停时间已过期,重置管理员账户状态 */
                self::getDb()->createCommand()->update(self::tableName(), [
                    'status' => self::STATUS_NORMAL,
                    'deny_end_time' => null,
                    'updated_at' => now(),
                ], 'id=:adminId', ['adminId' => $adminId])->execute();

                return false;
            }
        }
    }

    /**
     * 封停管理员
     * @param int $adminId 管理员ID
     * @param null|string $expireData 封停过期时间
     * @return true
     * @throws \yii\db\Exception
     */
    public static function banUser($adminId, $expireData = null)
    {
        self::getDb()->createCommand()->update(self::tableName(), [
            'status' => self::STATUS_DENY,
            'deny_end_time' => $expireData,
            'updated_at' => now(),
        ], 'id=:adminId', ['adminId' => $adminId])->execute();

        return true;
    }

    /**
     * [[adminUser]]表认证类型转管理员登录日志表认证类型
     * @param int $safeWay [[adminUser]]表认证类型
     * @return int
     */
    public static function getLoginLogIdentifyType($safeWay)
    {
        switch ($safeWay) {
            case self::SAFE_AUTH_CLOSE:     // 基本认证
                return LoginLog::IDENTIFY_TYPE_BASE;
            case self::SAFE_AUTH_EMAIL:     // 邮箱认证
                return LoginLog::IDENTIFY_TYPE_EMAIL;
            case self::SAFE_AUTH_MESSAGE:   // 短信认证
                return LoginLog::IDENTIFY_TYPE_SMS;
            case self::SAFE_AUTH_OTP:       // 2FA认证
                return LoginLog::IDENTIFY_TYPE_MFA;
            default:
                return 250;
        }
    }

    /**
     * 定义属性标签
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => Yii::t('app.admin', 'the action items'),
            'parent' => Yii::t('app.admin', 'my supervisor'),
            'username' => Yii::t('app.admin', 'user name'),
            'password' => Yii::t('app.admin', 'the password'),
            'repassword' => Yii::t('app.admin', 'the repeat password'),
            'email' => Yii::t('app.admin', 'the email'),
            'an' => Yii::t('app.admin', 'the mobile area number'),
            'mobile' => Yii::t('app.admin', 'the mobile'),
            'group' => Yii::t('app.admin', 'the management group'),
            'deny_end_time' => Yii::t('app.admin', 'the closing time'),
            'usernameOrEmail' => Yii::t('app.admin', 'the email/username'),
        ];
    }

    /**
     * 规则定义
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'action', 'username', 'usernameOrEmail', 'email', 'an', 'mobile', 'group'], 'required'],
            ['action', 'in', 'range' => ['disabled', 'enabled'], 'message' => Yii::t('app.admin', 'the operation item is not correct')],
            ['parent', 'string', 'min' => 2, 'max' => 250],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['username', 'unique', 'on' => ['add']],
            ['password', 'required', 'on' => ['add', 'login-base']],
            ['password', 'string', 'min' => 6, 'max' => 25],
            ['password', 'match', 'pattern' => '/^(?=.*[a-zA-Z])(?=.*[0-9])[A-Za-z0-9]{6,25}$/i', 'message' => t('the password must contain both numbers and letters', 'app.admin')],
            ['repassword', 'required', 'when' => function ($model) {
                /* @var AdminUser $model */
                // 当验证场景是`新增`或者场景是`编辑`并且[[密码]]非空时验证必填。
                return $model->scenario == 'add' || ($model->scenario == 'edit' && !empty($model->password));
            }],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['email', 'email'],
            ['email', 'unique', 'on' => ['add']],
            ['email', 'unique', 'filter' => function ($query) {
                /* @var Query $query */
                $query->andWhere(['<>', 'id', $this->id]);
            }, 'on' => ['edit']],
            ['an', 'number'],
            ['mobile', 'string', 'min' => 5, 'max' => 11],
            ['mobile', 'validateMobileIsUnique'],
            ['group', 'integer'],
            ['deny_end_time', 'default', 'value' => null],
            ['deny_end_time', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * 自定义验证器 - 手机号唯一校验
     * @param $attribute
     * @param $params
     */
    public function validateMobileIsUnique($attribute, $params)
    {
        $an = $this->an;
        $mobile = $this->{$attribute};
        if ($this->scenario == 'add') {
            // 场景`新增`
            $result = self::activeQuery('id')->where(['an' => $an, 'mobile' => $mobile])->one();
            if (!empty($result)) {
                $this->addError($attribute, Yii::t('app', 'The cell phone number already exists'));
            }
        } elseif ($this->scenario == 'edit') {
            // 场景`编辑`
            $id = $this->id;
            $result = self::activeQuery('id')->where(['and', ['an' => $an, 'mobile' => $mobile], ['<>', 'id', $id]])->one();
            if (!empty($result)) {
                $this->addError($attribute, Yii::t('app', 'The cell phone number already exists'));
            }
        }
    }

    /**
     * 场景定义
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // 场景`新增`
        $scenarios['add'] = ['parent', 'username', 'password', 'repassword', 'email', 'an', 'mobile', 'group'];
        // 场景`解封和封停`
        $scenarios['status-action'] = ['id', 'action', 'deny_end_time'];
        // 场景`编辑`
        $scenarios['edit'] = ['password', 'repassword', 'email', 'an', 'mobile'];
        // 登录 - 基本校验
        $scenarios['login-base'] = ['usernameOrEmail', 'password'];

        return $scenarios;
    }

    /**
     * 附加行为
     * @return array
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();
        // 密码处理器
        $parentBehaviors['passwordBehavior'] = [
            'class' => PasswordBehavior::class,
            'attributes' => [
                CommonActiveRecord::EVENT_BEFORE_INSERT => 'password',
                CommonActiveRecord::EVENT_BEFORE_UPDATE => 'password',
            ],
        ];

        return $parentBehaviors;
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
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * 生成管理员身份码
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateIdentifyCode()
    {
        $identifyCode = random_string(true, 10);
        while (self::activeQuery('id')->where(['identify_code' => $identifyCode])->one()) {
            $identifyCode = random_string(true, 10);
        }

        return $identifyCode;
    }

    /**
     * 验证安全码
     * @param int $uid 用户ID
     * @param int $way 认证方式
     * @param string $code 认证码
     * @return true|string
     */
    public function verifySafeAuth($uid, $way, $code)
    {
        return true;
    }
}