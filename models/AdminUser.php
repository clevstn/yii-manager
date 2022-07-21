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
 * @property int $safe_auth 是否开启安全认证, 0:不开启 1:跟随系统 2:邮箱认证 3:短信认证 4:MFA认证
 * @property int $open_operate_log 是否开启操作日志, 0:关闭 1:跟随系统 2:开启
 * @property int $open_login_log 是否开启登录日志, 0:关闭 1:跟随系统 2:开启
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
 * @since 1.0
 */
class AdminUser extends CommonActiveRecord implements IdentityInterface
{
    // 路由分割符号
    const PATH_SPLIT_SYMBOL = '-';

    // 禁用
    const STATUS_DENY = 0;
    // 正常
    const STATUS_NORMAL = 1;

    // 关闭安全认证
    const SAFE_AUTH_CLOSE = 0;
    // 安全认证跟随系统设置
    const SAFE_AUTH_FOLLOW_SYSTEM = 1;
    // 邮箱认证
    const SAFE_AUTH_EMAIL = 2;
    // 短信认证
    const SAFE_AUTH_MESSAGE = 3;
    // MFA认证
    const SAFE_AUTH_OTP = 4;

    // 关闭操作日志
    const OPERATE_LOG_CLOSE = 0;
    // 操作日志设置跟随系统
    const OPERATE_LOG_FOLLOW = 1;
    // 开启操作日志
    const OPERATE_LOG_OPEN = 2;

    // 关闭登录日志
    const LOGIN_LOG_CLOSE = 0;
    // 登录日志设置跟随系统
    const LOGIN_LOG_FOLLOW = 1;
    // 开启登录日志
    const LOGIN_LOG_OPEN = 2;

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
     * 获取状态集合
     * @return array
     */
    public static function statusMap()
    {
        return [
            self::STATUS_DENY => Yii::t('app', 'closure'),
            self::STATUS_NORMAL => Yii::t('app', 'normal'),
        ];
    }

    /**
     * 获取安全认证选项集合
     * @return array
     */
    public static function safeMap()
    {
        return [
            self::SAFE_AUTH_CLOSE => Yii::t('app', 'close'),
            self::SAFE_AUTH_FOLLOW_SYSTEM => Yii::t('app', 'follow the system'),
            self::SAFE_AUTH_EMAIL => Yii::t('app', 'email authentication'),
            self::SAFE_AUTH_MESSAGE => Yii::t('app', 'SMS authentication'),
            self::SAFE_AUTH_OTP => Yii::t('app', 'OTP authentication'),
        ];
    }

    /**
     * 获取操作日志,操作项集合
     * @return array
     */
    public static function operationMap()
    {
        return [
            self::OPERATE_LOG_CLOSE => Yii::t('app', 'close'),
            self::OPERATE_LOG_FOLLOW => Yii::t('app', 'follow the system'),
            self::OPERATE_LOG_OPEN => Yii::t('app', 'open'),
        ];
    }

    /**
     * 获取登陆日志,操作项集合
     * @return array
     */
    public static function loginMap()
    {
        return [
            self::LOGIN_LOG_CLOSE => Yii::t('app', 'close'),
            self::LOGIN_LOG_FOLLOW => Yii::t('app', 'follow the system'),
            self::LOGIN_LOG_OPEN => Yii::t('app', 'open'),
        ];
    }

    /**
     * 获取状态标签
     * @param int $status 状态
     * @param boolean $isHtml 是否是html
     * @return string
     */
    public static function getStatusLabel($status, $isHtml = false)
    {
        switch ($status) {
            case self::STATUS_DENY:
                $label = Yii::t('app', 'disable');
                if ($isHtml) {
                    return '<span class="label label-danger">' . $label . '</span>';
                }

                return $label;
            case self::STATUS_NORMAL:
                $label = Yii::t('app', 'normal');
                if ($isHtml) {
                    return '<span class="label label-success">' . $label . '</span>';
                }

                return $label;
            default:
                $label = Yii::t('app', 'unknown');
                if ($isHtml) {
                    return '<span class="label label-default">' . $label . '</span>';
                }

                return $label;
        }
    }

    /**
     * 获取是否开启安全认证标签
     * @param int $safeAuth 是否开启安全认证
     * @return string
     */
    public static function getIsSafeAuthLabel($safeAuth)
    {
        switch ($safeAuth) {
            case self::SAFE_AUTH_CLOSE:
                return Yii::t('app', 'close');
            case self::SAFE_AUTH_FOLLOW_SYSTEM:
                return Yii::t('app', 'follow the system');
            case self::SAFE_AUTH_EMAIL:
                return Yii::t('app', 'email authentication');
            case self::SAFE_AUTH_MESSAGE:
                return Yii::t('app', 'SMS authentication');
            case self::SAFE_AUTH_OTP:
                return Yii::t('app', 'OTP authentication');
            default:
                return Yii::t('app', 'unknown');
        }
    }

    /**
     * 获取是否开启操作日志标签
     * @param int $isOpenOperateLog 是否开启操作日志
     * @return string
     */
    public static function getIsOpenOperateLabel($isOpenOperateLog)
    {
        switch ($isOpenOperateLog) {
            case self::OPERATE_LOG_CLOSE:
                return Yii::t('app', 'close');
            case self::OPERATE_LOG_FOLLOW:
                return Yii::t('app', 'follow the system');
            case self::OPERATE_LOG_OPEN:
                return Yii::t('app', 'open');
            default:
                return Yii::t('app', 'unknown');
        }
    }

    /**
     * 获取是否开启登录日志标签
     * @param $isOpenLoginLog
     * @return string
     */
    public static function getIsOpenLoginLogLabel($isOpenLoginLog)
    {
        switch ($isOpenLoginLog) {
            case self::LOGIN_LOG_CLOSE:
                return Yii::t('app', 'close');
            case self::LOGIN_LOG_FOLLOW:
                return Yii::t('app', 'follow the system');
            case self::LOGIN_LOG_OPEN:
                return Yii::t('app', 'open');
            default:
                return Yii::t('app', 'unknown');
        }
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
    public static function freezeUser($adminId, $expireData = null)
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
            case self::SAFE_AUTH_EMAIL: // 邮箱认证
                return LoginLog::IDENTIFY_TYPE_EMAIL;
            case self::SAFE_AUTH_MESSAGE:
                return LoginLog::IDENTIFY_TYPE_SMS;
            case self::SAFE_AUTH_OTP:
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
            'action' => Yii::t('app', 'the action items'),
            'parent' => Yii::t('app', 'my supervisor'),
            'username' => Yii::t('app', 'user name'),
            'password' => Yii::t('app', 'the password'),
            'repassword' => Yii::t('app', 'the repeat password'),
            'email' => Yii::t('app', 'the email'),
            'an' => Yii::t('app', 'the mobile area number'),
            'mobile' => Yii::t('app', 'the mobile'),
            'safe_auth' => Yii::t('app', 'whether to enable security authentication'),
            'open_operate_log' => Yii::t('app', 'whether to turn on the operation log'),
            'open_login_log' => Yii::t('app', 'whether log on or not'),
            'group' => Yii::t('app', 'the management group'),
            'deny_end_time' => Yii::t('app', 'the closing time'),
            'usernameOrEmail' => Yii::t('app', 'the email/username'),
        ];
    }

    /**
     * 规则定义
     * @return array
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['action', 'required'],
            ['action', 'in', 'range' => ['disabled', 'enabled'], 'message' => Yii::t('app', 'the operation item is not correct')],
            ['parent', 'string', 'min' => 2, 'max' => 250],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['username', 'unique', 'on' => ['add']],
            ['usernameOrEmail', 'required'],
            ['password', 'required', 'on' => ['add', 'login-base']],
            ['password', 'string', 'min' => 6, 'max' => 25],
            ['password', 'match', 'pattern' => '/^[1-9a-z][1-9a-z_\-+.*!@#$%&=|~]{5,24}$/i'],
            ['repassword', 'required', 'when' => function ($model) {
                /* @var AdminUser $model */
                // 当验证场景是`新增`或者场景是`编辑`并且[[密码]]非空时验证必填。
                return $model->scenario == 'add' || ($model->scenario == 'edit' && !empty($model->password));
            }],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'on' => ['add']],
            ['email', 'unique', 'filter' => function ($query) {
                /* @var Query $query */
                $query->andWhere(['<>', 'id', $this->id]);
            }, 'on' => ['edit']],
            ['an', 'required'],
            ['an', 'number'],
            ['mobile', 'required'],
            ['mobile', 'string', 'min' => 5, 'max' => 11],
            ['mobile', 'validateMobileIsUnique'],
            ['safe_auth', 'required'],
            ['safe_auth', 'in', 'range' => array_keys(self::safeMap())],
            ['open_operate_log', 'required'],
            ['open_operate_log', 'in', 'range' => array_keys(self::operationMap())],
            ['open_login_log', 'required'],
            ['open_login_log', 'in', 'range' => array_keys(self::loginMap())],
            ['group', 'required'],
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
            $result = self::query('id')->where(['an' => $an, 'mobile' => $mobile])->one();
            if (!empty($result)) {
                $this->addError($attribute, Yii::t('app', 'The cell phone number already exists'));
            }
        } elseif ($this->scenario == 'edit') {
            // 场景`编辑`
            $id = $this->id;
            $result = self::query('id')->where(['and', ['an' => $an, 'mobile' => $mobile], ['<>', 'id', $id]])->one();
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
        $scenarios['add'] = ['parent', 'username', 'password', 'repassword', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log', 'group'];
        // 场景`解封和封停`
        $scenarios['status-action'] = ['id', 'action', 'deny_end_time'];
        // 场景`编辑`
        $scenarios['edit'] = ['password', 'repassword', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log'];
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
     * 获取用户的安全认证方式
     * @param int $userId 用户ID
     * @return int|mixed
     */
    public function getSafeWays($userId)
    {
        $user = self::query('safe_auth')->where(['id' => $userId])->one();
        if (empty($user)) {
            return self::SAFE_AUTH_CLOSE;
        }

        switch ($user['safe_auth']) {
            case self::SAFE_AUTH_FOLLOW_SYSTEM: // 跟随系统
                $value = SystemConfig::get('ADMIN_GROUP.ADMIN_CCEE', self::SAFE_AUTH_CLOSE);
                switch ($value) {
                    case 1: // 邮箱认证
                        return self::SAFE_AUTH_EMAIL;
                    case 2: // 短信认证
                        return self::SAFE_AUTH_MESSAGE;
                    case 3: // MFA认证
                        return self::SAFE_AUTH_OTP;
                    case 0: // 关闭安全认证
                    default:
                        return self::SAFE_AUTH_CLOSE;
                }
                break;
            default:
                return $user['safe_auth'];
        }
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
        while (self::query('id')->where(['identify_code' => $identifyCode])->one()) {
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