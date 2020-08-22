<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\common;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\builder\traits\Http;
use yii\filters\AccessControl;
use app\builder\filters\RbacFilter;

/**
 * 控制器继承类
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class CommonController extends Controller
{
    use Http;

    /**
     * 后台首页
     * @var string
     * @since 1.0
     */
    public $homeUrl = '/admin/index/index';

    /**
     * Yii-manager layouts.
     *
     * @var string
     */
    public $layout = '@builder/layouts/layout.php';

    /**
     * Verbs to specify the actions.
     *
     * @var array
     */
    public $actionVerbs = [];

    /**
     * Define actions that do not require authorization.
     *
     * @var array
     */
    public $guestActions = [];

    /**
     * Register undetected action ids for RBAC.
     *
     * @var array
     */
    public $undetectedActions = [];

    /**
     * {@inheritdoc}
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        parent::init();
        /* 定义后台首页路径 */
        Yii::$app->homeUrl = $this->homeUrl;
    }

    /**
     * Behaviors
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();
        $actionVerbsFilter = $this->verbsFilter();
        $accessCtrlFilter = $this->accessControlFilter();
        $rgacFilter = $this->rbacFilter();

        return array_merge(
            $parentBehaviors,
            $actionVerbsFilter,
            $accessCtrlFilter,
            $rgacFilter
        );
    }

    /**
     * This is action verbs filters.
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @see VerbFilter
     */
    public function verbsFilter()
    {
        if (!empty($this->actionVerbs)) {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => $this->actionVerbs,
                ],
            ];
        }

        return [];
    }

    /**
     * This is access control filters.
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @see AccessControl
     * @see \yii\filters\AccessRule
     */
    public function accessControlFilter()
    {
        if (empty($this->guestActions) || !in_array($this->action->id, $this->guestActions, true)) {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'user' => 'adminUser',
                    'only' => [$this->action->id],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }

        return [];
    }

    /**
     * Rbac controls filters.
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function rbacFilter()
    {
        if (
            !in_array($this->action->id, $this->guestActions, true)
            && (empty($this->undetectedActions) || !in_array($this->action->id, $this->undetectedActions, true))
        ) {
            return [
                'rbac' => [
                    'class' => RbacFilter::className(),
                ],
            ];
        }

        return [];
    }
}