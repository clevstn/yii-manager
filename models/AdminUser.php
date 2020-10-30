<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\models;

use yii\db\Query;
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
 * @property-read string $authKey cookie认证密匙
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

    /**
     * 操作项
     * - disabled 封停
     * - enabled  解封
     * @var string
     */
    public $action;

    // 路由分割符号
    const PATH_SPLIT_SYMBOL = '-';

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
     * 定义表格名
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * 定义属性标签
     * @return array|string[]
     * @author cleverstone
     * @since 1.0
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => '操作项',
            'parent' => '我的上级',
            'username' => '用户名',
            'password' => '密码',
            'repassword' => '重复密码',
            'email' => '邮箱',
            'an' => '电话区号',
            'mobile' => '手机号',
            'safe_auth' => '是否开启安全认证',
            'open_operate_log' => '是否开启操作日志',
            'open_login_log' => '是否开启登录日志',
            'group' => '管理组',
            'deny_end_time' => '封停截止时间',
        ];
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
            ['id', 'required'],
            ['action', 'required'],
            ['action', 'in', 'range' => ['disabled', 'enabled'], 'message' => '操作项不正确'],
            ['parent', 'string', 'min' => 2, 'max' => 250],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['password', 'required', 'on' => ['add']],
            ['password', 'string', 'min' => 6, 'max' => 25],
            ['password', 'match', 'pattern' => '/^[1-9a-z][1-9a-z_\-+.*!@#$%&=|~]{5,24}$/i', 'message' => '密码存在敏感字符请重写输入'],
            ['repassword', 'required', 'when' => function ($model) {
                /* @var AdminUser $model */
                // 当验证场景是`新增`或者场景是`编辑`并且[[密码]]非空时验证必填。
                return $model->scenario == 'add' || ($model->scenario == 'edit' && !empty($model->password));
            }],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码输入不一致'],
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
            ['safe_auth', 'in', 'range' => array_keys(self::$safeMap)],
            ['open_operate_log', 'required'],
            ['open_operate_log', 'in', 'range' => array_keys(self::$operationMap)],
            ['open_login_log', 'required'],
            ['open_login_log', 'in', 'range' => array_keys(self::$loginMap)],
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
     * @author cleverstone
     * @since 1.0
     */
    public function validateMobileIsUnique($attribute, $params)
    {
        $an = $this->an;
        $mobile = $this->{$attribute};
        if ($this->scenario == 'add') {
            // 场景`新增`
            $result = self::query('id')->where(['an' => $an, 'mobile' => $mobile])->one();
            if (!empty($result)) {
                $this->addError($attribute, '手机号码已被占用');
            }
        } elseif ($this->scenario == 'edit') {
            // 场景`编辑`
            $id = $this->id;
            $result = self::query('id')->where(['and', ['an' => $an, 'mobile' => $mobile], ['<>', 'id', $id]])->one();
            if (!empty($result)) {
                $this->addError($attribute, '手机号码已被占用');
            }
        }
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
        // 场景`解封和封停`
        $scenarios['status_action'] = ['id', 'action', 'deny_end_time'];
        // 场景`编辑`
        $scenarios['edit'] = ['password', 'repassword', 'email', 'an', 'mobile', 'safe_auth', 'open_operate_log', 'open_login_log'];

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
            'class' => PasswordBehavior::class,
            'attributes' => [
                CommonActiveRecord::EVENT_BEFORE_INSERT => 'password',
                CommonActiveRecord::EVENT_BEFORE_UPDATE => 'password',
            ],
        ];

        return $parentBehaviors;
    }

    /**
     * 获取状态标签
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
     * 获取是否开启安全认证标签
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
     * 获取是否开启操作日志标签
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
     * 获取是否开启登录日志标签
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
     * 通过用户`id`获取模型实例
     * @param int|string $id id
     * @return AdminUser|IdentityInterface|null
     * @author cleverstone <yang_hui_lei@163.com>
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
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * 通过用户名获取模型实例
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

    /**
     * 生成管理员身份码
     * @return string
     * @throws \yii\base\Exception
     * @author cleverstone
     * @since 1.0
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
     * 组成[[path]]
     * @param int $pid 父级`id`
     * @param string $parentPath 父级`path`
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function makePath($pid, $parentPath)
    {
        return $parentPath . $pid . self::PATH_SPLIT_SYMBOL;
    }
}