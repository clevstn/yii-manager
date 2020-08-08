<?php
// +----------------------------------------------------------------------
// | yii-manager控制器继承类
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\common;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\builder\filters\RbacFilter;

/**
 * @property array $get
 * @property array $post
 * @property boolean $isGet
 * @property boolean $isPost
 * @property boolean $isAjax
 * @property string|null $domain
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
abstract class CommonController extends Controller
{

    /**
     * Yii-manager layouts.
     *
     * @var string
     */
    public $layout = '@builder/layouts/layout.php';

    /**
     * By call this attributes `get` `post`, this is effective.
     *
     * @var bool
     */
    public $emptyStrToNull = true;

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

    /**
     * Get verb `get` info
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getGet()
    {
        $get = $this->request->get();
        if (!empty($get)) {
            foreach ($get as $queryStr => &$value) {
                $value = $value === '' && true === $this->emptyStrToNull ? null : $value;
            }

            return $get;
        }

        return [];
    }

    /**
     * Get verb `post` info
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getPost()
    {
        $post = $this->request->post();
        if (!empty($post)) {
            foreach ($post as $bodyStr => &$value) {
                $value = $value === '' && true === $this->emptyStrToNull ? null : $value;
            }

            return $post;
        }

        return [];
    }

    /**
     * Detect get
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsGet()
    {
        return $this->request->isGet;
    }

    /**
     * Detect post
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsPost()
    {
        return $this->request->isPost;
    }

    /**
     * Detect ajax
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsAjax()
    {
        return $this->request->getIsAjax();
    }

    /**
     * Get domain
     *
     * @return string|null
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getDomain()
    {
        return $this->request->hostInfo;
    }
}