<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\models\SystemConfig;
use Yii;
use app\builder\common\CommonController;
use yii\web\View;

/**
 * 系统设置
 * @author cleverstone
 * @since ym1.0
 */
class SystemSettingController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get', 'post'],
    ];

    /**
     * 系统设置
     * @return string
     * @throws \Throwable
     */
    public function actionIndex()
    {
        if ($this->isPost) {
            $this->emptyStrToNull = false;

            $post = $this->post;
            unset($post['_csrf']);

            $result = Yii::$app->config->set($post);
            if ($result !== true) {
                return $this->asFail($result);
            }

            return $this->asSuccess('操作成功');
        } else {
            // group => [code => ...]
            $configMap = Yii::$app->config->getFromDb();
            $groupMap = SystemConfig::query('*')->where(['type' => SystemConfig::TYPE_GROUP])->all();

            foreach ($configMap as &$item) {
                foreach ($item as &$value) {
                    if (!empty($value['options'])) {
                        $value['options'] = SystemConfig::resolveOption($value['options']);
                    }
                }
            }

            $params = [
                'config' => $configMap,
                'group' => $groupMap,
            ];
            // Register js script
            $jsScript = $this->view->renderPhpFile(Yii::getAlias('@app/admin/views/system-setting/js.php'), $params);
            $this->view->registerJs(preg_script($jsScript), View::POS_END);

            return $this->render('index', $params);
        }
    }
}