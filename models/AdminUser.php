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
use app\builder\common\CommonActiveRecord;

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
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AdminUser extends CommonActiveRecord implements IdentityInterface
{
    /**
     * 我的上级
     * @var string
     */
    public $parent;

    /**
     * 重复密码
     * @var string
     */
    public $repassword;

    // 禁用
    const STATUS_DENY = 0;

    // 正常
    const STATUS_NORMAL = 1;

    /**
     * @var string[]
     */
    public static $statusMap = [
        self::STATUS_DENY => '封停',
        self::STATUS_NORMAL => '正常',
    ];

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

    /**
     * @var array
     */
    public static $safeMap = [
        self::SAFE_AUTH_CLOSE => '关闭',
        self::SAFE_AUTH_FOLLOW_SYSTEM => '跟随系统',
        self::SAFE_AUTH_EMAIL => '邮箱认证',
        self::SAFE_AUTH_MESSAGE => '短信认证',
        self::SAFE_AUTH_OTP => 'OTP认证',
    ];

    // 关闭操作日志
    const OPERATE_LOG_CLOSE = 0;

    // 操作日志设置跟随系统
    const OPERATE_LOG_FOLLOW = 1;

    // 开启操作日志
    const OPERATE_LOG_OPEN = 2;

    /**
     * @var string[]
     */
    public static $operationMap = [
        self::OPERATE_LOG_CLOSE => '关闭',
        self::OPERATE_LOG_FOLLOW => '跟随系统',
        self::OPERATE_LOG_OPEN => '开启',
    ];

    // 关闭登录日志
    const LOGIN_LOG_CLOSE = 0;

    // 登录日志设置跟随系统
    const LOGIN_LOG_FOLLOW = 1;

    // 开启登录日志
    const LOGIN_LOG_OPEN = 2;

    /**
     * @var string[]
     */
    public static $loginMap = [
        self::LOGIN_LOG_CLOSE => '关闭',
        self::LOGIN_LOG_FOLLOW => '跟随系统',
        self::LOGIN_LOG_OPEN => '开启',
    ];

    /**
     * 设置表格名
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * 规则定义
     * @return array
     * @author cleverstone
     * @since 1.0
     */
    public function rules()
    {
        return [
            ['parent', 'string', 'min' => 3, 'max' => 250],
            [['username', 'password', 'repassword', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log', 'group'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 20],
            ['password', 'string', 'min' => 6, 'max' => 25],
            ['password', 'match', 'pattern' => '/^[1-9a-z][1-9a-z_\-+.*!@#$%&=|~]{5,24}$/i', 'message' => '密码存在敏感字符请重写输入'],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码输入不一致'],
            ['email', 'email'],
            ['an', 'number'],
            ['mobile', 'string', 'min' => 5, 'max' => 11],
            ['safe_auth', 'in', 'range' => array_keys(self::$safeMap)],
            ['open_operate_log', 'in', 'range' => array_keys(self::$operationMap)],
            ['open_login_log', 'in', 'range' => array_keys(self::$loginMap)],
            ['group', 'integer'],
        ];
    }

    /**
     * 场景定义
     * @return array|array[]
     * @author cleverstone
     * @since 1.0
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // 场景`新增`
        $scenarios['add'] = ['parent', 'username', 'password', 'repassword', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log', 'group'];

        return $scenarios;
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
     * 获取状态label
     * @param int $status 状态
     * @param boolean $isHtml 是否是html
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function getStatusLabel($status, $isHtml = false)
    {
        switch ($status) {
            case self::STATUS_DENY:
                if ($isHtml) {
                    return '<span class="label label-danger">禁用</span>';
                }

                return '禁用';
            case self::STATUS_NORMAL:
                if ($isHtml) {
                    return '<span class="label label-success">正常</span>';
                }

                return '正常';
            default:
                if ($isHtml) {
                    return '<span class="label label-default">未知</span>';
                }

                return '未知';
        }
    }

    /**
     * 获取是否开启安全认证label
     * @param int $safeAuth 是否开启安全认证
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function getIsSafeAuthLabel($safeAuth)
    {
        switch ($safeAuth) {
            case self::SAFE_AUTH_CLOSE:
                return '关闭';
            case self::SAFE_AUTH_FOLLOW_SYSTEM:
                return '跟随系统';
            case self::SAFE_AUTH_EMAIL:
                return '邮箱认证';
            case self::SAFE_AUTH_MESSAGE:
                return '短信认证';
            case self::SAFE_AUTH_OTP:
                return 'MFA认证';
            default:
                return '未知';
        }
    }

    /**
     * 获取是否开启操作日志label
     * @param int $isOpenOperateLog 是否开启操作日志
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function getIsOpenOperateLabel($isOpenOperateLog)
    {
        switch ($isOpenOperateLog) {
            case self::OPERATE_LOG_CLOSE:
                return '关闭';
            case self::OPERATE_LOG_FOLLOW:
                return '跟随系统';
            case self::OPERATE_LOG_OPEN:
                return '开启';
            default:
                return '未知';
        }
    }

    /**
     * 获取是否开启登录日志label
     * @param $isOpenLoginLog
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function getIsOpenLoginLogLabel($isOpenLoginLog)
    {
        switch ($isOpenLoginLog) {
            case self::LOGIN_LOG_CLOSE:
                return '关闭';
            case self::LOGIN_LOG_FOLLOW:
                return '跟随系统';
            case self::LOGIN_LOG_OPEN:
                return '开启';
            default:
                return '未知';
        }
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