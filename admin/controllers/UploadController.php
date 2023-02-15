<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use Yii;
use app\builder\common\CommonController;

/**
 * 附件上传
 * @author cleverstone
 * @since ym1.0
 */
class UploadController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'add' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'add',
    ];

    /**
     * 添加附件
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function actionAdd()
    {
        if ($this->isPost) {
            $bodyParams = $this->post;
            $issetResult = notset_return($bodyParams, [
                'name',     // 字段
            ]);

            if ($issetResult !== true) {
                return $issetResult;
            }

            $result = Yii::$app->uploads->execute($bodyParams['name'], $bodyParams);
            if (is_array($result)) {
                return $this->asSuccess('上传成功', $result);
            }

            return $this->asFail($result);
        } else {
            $this->emptyStrToNull = false;
            $queryParam = $this->get;
            $queryParam = empty_set_default($queryParam, ['name' => 'file']);

            $this->setLayoutViewPath();
            return $this->render('index', [
                'fields' => $queryParam,
            ]);
        }
    }
}