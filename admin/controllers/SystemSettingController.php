<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\helper\ConfigDefineHelper;
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
        'load' => ['get'],
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

    /**
     * 加载本地配置定义
     * @return string
     * @throws \yii\base\ErrorException
     */
    public function actionLoad()
    {
        $configDefine = ConfigDefineHelper::normalizeConfigDefine();
        $groupDefine = ConfigDefineHelper::normalizeGroupDefine();
        $mergeMap = array_merge($groupDefine, $configDefine);
        // ['code', 'value', 'control', 'options', 'name', 'desc', 'tips', 'type', 'group', 'created_at'],

        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($mergeMap as $item) {
                $one = SystemConfig::findOne(['code' => $item[0]]);
                if (empty($one)) {
                    $model = new SystemConfig();
                    $model->setAttributes([
                        'code' => $item[0],
                        'value' => $item[1],
                        'control' => $item[2],
                        'options' => $item[3],
                        'name' => $item[4],
                        'desc' => $item[5],
                        'tips' => $item[6],
                        'type' => $item[7],
                        'group' => $item[8],
                        'created_at' => $item[9],
                    ]);

                    if (!$model->save()) {
                        throw new \Exception($model->error);
                    }
                }
            }

            $trans->commit();

            // 清除缓存配置
            Yii::$app->config->invalidateCache();

            return $this->asSuccess(t('operate successfully', 'app.admin'));
        } catch (\Exception $e) {
            $trans->rollBack();
            return $this->asFail($e->getMessage());
        }
    }
}