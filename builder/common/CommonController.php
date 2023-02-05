<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
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
 * @author cleverstone
 * @since ym1.0
 */
class CommonController extends Controller
{
    use Http;

    /**
     * @var string 后台首页
     */
    public $homeUrl = '/admin/index/index';

    /**
     * 视图继承定义
     * @var string Yii-manager layouts.
     */
    public $layout = '@builder/layouts/layout.php';

    /**
     * 请求动作定义
     * @var array Verbs to specify the actions.
     */
    public $actionVerbs = [];

    /**
     * 只有游客可以访问的action-id。
     * @var array Define actions that don't require authorization.
     */
    public $guestActions = [];

    /**** 属性：$guestActions、$publicActions都不定义action-id，则是只有认证用户可以访问的action-id ****/

    /**
     * 游客和认证用户都可以访问的action-id。
     * @var array The guest and authenticated users can access current action.
     */
    public $publicActions = [];

    /**
     * 只有认证用户可以访问但不需要RBAC限制的action-id。
     * 注：
     *  因为视图无法检查到该配置项。
     *  因此，
     *  如果使用`ViewBuilder`进行视图组件构建，必须加入admin/config/menu.php下的whiteList配置项；
     *  如果自定义页面，可以不加入admin/config/menu.php下的whiteList配置项；
     *  建议两种情况都加入（可以用于行为记录）。
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
        } else {
            return [];
        }
    }

    /**
     * This is access control filters.
     * @return array
     */
    public function accessControlFilter()
    {
        if (
            !in_array($this->action->id, $this->guestActions, true)
            && !in_array($this->action->id, $this->publicActions, true)
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
        } else {
            return [];
        }
    }
}