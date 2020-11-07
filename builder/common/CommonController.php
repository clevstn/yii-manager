<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

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
     * @var string 后台首页
     */
    public $homeUrl = '/admin/index/index';

    /**
     * @var string Yii-manager layouts.
     */
    public $layout = '@builder/layouts/layout.php';

    /**
     * @var array Verbs to specify the actions.
     */
    public $actionVerbs = [];

    /**
     * @var array Define actions that do not require authorization.
     */
    public $guestActions = [];

    /**
     * @var array The guest and authenticated users can access current action.
     */
    public $publicActions = [];

    /**
     * @var array Register undetected action ids for RBAC.
     */
    public $undetectedActions = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        /* 定义后台首页路径 */
        Yii::$app->homeUrl = $this->homeUrl;
    }

    /**
     * Behaviors
     * @return array
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
     * @return array
     */
    public function verbsFilter()
    {
        if (!empty($this->actionVerbs)) {
            return [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => $this->actionVerbs,
                ],
            ];
        }

        return [];
    }

    /**
     * This is access control filters.
     * @return array
     */
    public function accessControlFilter()
    {
        if (
            empty($this->guestActions) && empty($this->publicActions)
            || !in_array($this->action->id, $this->guestActions, true) && !in_array($this->action->id, $this->publicActions, true)
        ) {
            // 只有认证用户可以访问
            return [
                'access' => [
                    'class' => AccessControl::class,
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
        } elseif (in_array($this->action->id, $this->guestActions, true)) {
            // 只有游客可以访问
            return [
                'access' => [
                    'class' => AccessControl::class,
                    'user' => 'adminUser',
                    'only' => [$this->action->id],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                    ],
                ],
            ];
        } else {
            // 游客和认证用户都可以访问
            return [];
        }
    }

    /**
     * Rbac controls filters.
     * @return array
     */
    public function rbacFilter()
    {
        if (
            !in_array($this->action->id, $this->guestActions, true)
            && !in_array($this->action->id, $this->publicActions, true)
            && !in_array($this->action->id, $this->undetectedActions, true)
        ) {
            return [
                'rbac' => [
                    'class' => RbacFilter::class,
                ],
            ];
        }

        return [];
    }
}