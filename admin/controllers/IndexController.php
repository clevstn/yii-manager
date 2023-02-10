<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\models\AdminUser;
use app\models\AdminUserQuickAction;
use app\models\AuthGroups;
use app\models\AuthMenu;
use app\models\AuthRelations;
use Yii;
use app\builder\common\CommonController;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * 首页仪表盘
 * @author cleverstone
 * @since ym1.0
 */
class IndexController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
        'quick-setting' => ['get', 'post'],
        'quick-list' => ['get'],
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'index',
        'quick-setting',
        'quick-list',
    ];

    /**
     * 后台首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 设置快捷项
     * @return string
     */
    public function actionQuickSetting()
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;
        if ($this->isPost) {
            if (empty($identify)) {
                return $this->asFail(t('Login authorization error', 'app.admin'));
            }

            $bodyParams = $this->post;
            $bodyParams['admin_id'] = $identify->id;

            $model = new AdminUserQuickAction();
            $model->setScenario('one-add');

            // 数据校验
            if ($model->load($bodyParams)) {
                if ($model->validate()) {
                    if ($bodyParams['isChecked'] == 1) {
                        // 新增
                        if ($model->save(false)) {
                            return $this->asSuccess(t('operate successfully', 'app.admin'));
                        }

                        return $this->asFail($model->error);
                    } else {
                        // 删除
                        $row = AdminUserQuickAction::deleteAll(['admin_id' => $bodyParams['admin_id'], 'menu_id' => $bodyParams['menu_id']]);
                        if ($row) {
                            return $this->asSuccess(t('operate successfully', 'app.admin'));
                        }

                        return $this->asFail(t('operation failure', 'app.admin'));
                    }
                }

                return $this->asFail($model->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));

        } else {
            if (empty($identify)) {
                return '';
            }

            $group = $identify->group;
            $adminId = $identify->id;

            // 获取允许快捷设置菜单项
            if ($group == AuthGroups::ADMINISTRATOR_GROUP) {
                $allowQuickActions = AuthMenu::query([
                    'id',
                    'label',
                    'icon',
                ])
                    ->where(['is_quick' => AuthMenu::QUICK_YES])
                    ->all();
            } else {
                $allowQuickActions = AuthRelations::query([
                    'm.id',
                    'm.label',
                    'm.icon',
                ], 'r')
                    ->leftJoin(['m' => AuthMenu::tableName()], 'r.menu_id=m.id')
                    ->where(['r.group_id' => $group])
                    ->andWhere(['m.is_quick' => AuthMenu::QUICK_YES])
                    ->all();
            }

            // 获取已经设置的快捷项
            $actionColumns = AdminUserQuickAction::query('menu_id')
                ->where(['admin_id' => $adminId])
                ->column();

            foreach ($allowQuickActions as &$item) {
                if (ArrayHelper::isIn($item['id'], $actionColumns)) {
                    $item['_is_action'] = 1;
                } else {
                    $item['_is_action'] = 0;
                }
            }

            $this->setLayoutViewPath();

            return $this->render('quick-setting', [
                'allowQuickActions' => $allowQuickActions,
            ]);
        }
    }


    public function actionQuickList()
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;
        if (!$identify) {
            return $this->asSuccess(t('Request successful', 'app.admin'));
        }

        $list = AdminUserQuickAction::query([
            'm.id',
            'm.label',
            'm.icon',
            'm.src',
            'm.link_type',
        ], 'a')->leftJoin(['m' => AuthMenu::tableName()], 'a.menu_id=m.id')
            ->where(['a.admin_id' => $identify->id])
            ->all();

        foreach ($list as &$item) {
            if ($item['link_type'] == AuthMenu::LINK_TYPE_ROUTE) {
                $item['url'] = Url::to(['/' . trim($item['src'], '/'), '__partial__' => 1], '');
            } else {
                $item['url'] = $item['src'];
            }
        }

        return $this->asSuccess(t('Request successful', 'app.admin'), $list);
    }
}
