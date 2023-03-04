<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin;

use app\models\AdminUser;
use app\models\AuthGroups;
use Yii;
use yii\base\ActionEvent;
use yii\web\Response;
use app\admin\controllers\SiteController;

/**
 * 后台管理模块
 * @author cleverstone
 * @since ym1.0
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\admin\controllers';

    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setErrorHandler();

        $this->setEventHandler();
    }

    /**
     * 绑定模块事件处理器
     *
     * 注：两种为组件绑定事件处理器的方法，第一种比第二种运行提前。
     */
    public function setEventHandler()
    {
        // 后台行为记录
        Yii::$app->response->on(Response::EVENT_AFTER_SEND, ['app\eventhandlers\AdminBehaviorRecordHandler', 'handleClick']);
        //Event::on(Response::className(), Response::EVENT_AFTER_SEND, ['app\eventhandlers\AdminBehaviorRecordHandler', 'handleClick'], null, false);

        $this->setWebsiteControl();
    }

    /**
     * 站点维护检测
     * @return void
     */
    public function setWebsiteControl()
    {
        $this->on(self::EVENT_BEFORE_ACTION, function ($event) {
            $config = Yii::$app->config;
            $isDenyAccess =  $config->get('WEBSITE_GROUP.WEBSITE_SWITCH');
            /* @var AdminUser $identity */
            $identity = Yii::$app->adminUser->identity;

            // 判断后台是否开启维护开关
            if (
                is_numeric($isDenyAccess)
                && $isDenyAccess == 1
                && !empty($identity)
                && $identity->group !== AuthGroups::ADMINISTRATOR_GROUP
            ) {
                // 已开启网站维护,获取维护标语
                $denyTips = $config->get('WEBSITE_GROUP.WEBSITE_DENY_TIPS');

                // 闪存维护标语
                Yii::$app->session->setFlash(SiteController::$flashErrorIdentify, $denyTips);

                // 退出登录
                Yii::$app->adminUser->logout(false);

                // 退出后提示维护
                $response = Yii::$app->response;
                if (Yii::$app->request->isAjax) {
                    $response->format = Response::FORMAT_JSON;
                    $response->data = [
                        'code' => 500,
                        'msg' => $denyTips,
                    ];
                } else {
                    $response->redirect('/admin/site/login');
                }

                // 停止控制器后续访问动作的执行
                /* @var ActionEvent $event */
                $event->isValid = false;
                // 停止后续事件执行
                $event->handled = true;
            }
        });
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        // 单点登录和封号检测
        $this->on(self::EVENT_BEFORE_ACTION, ['app\eventhandlers\RouteControlHandler', 'handleClick']);

        return parent::beforeAction($action);
    }

    /**
     * 设置当前模块的错误处理动作
     */
    public function setErrorHandler()
    {
        Yii::$app->errorHandler->errorAction = 'admin/error/error';
    }
}
