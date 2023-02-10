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
        'quick-setting' => ['get'],
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'index',
        'quick-setting',
    ];

    /**
     * 后台首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionQuickSetting()
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;
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
