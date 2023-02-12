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
     * @throws \yii\base\Exception
     */
    public function actionAdd()
    {
        if ($this->isPost) {
            $this->emptyStrToNull = false;
            $bodyParams = $this->post;

            $issetResult = isset_return($bodyParams, [
                'scenario', // 场景
                'name',     // 字段
                'saveDirectory', //保存目录
                'pathPrefix', // 路径前缀
                'isBase64', // 是否是base64
            ]);
            if ($issetResult !== true) {
                return $issetResult;
            }

            $result = Yii::$app->uploads->execute(
                $bodyParams['name'],
                $bodyParams['saveDirectory'],
                $bodyParams['pathPrefix'],
                $bodyParams['scenario'],
                !!$bodyParams['isBase64']
            );

            if ($result === true) {
                return $this->asSuccess('提交成功', time());
            }

            return $this->asFail($result);
        } else {
            $this->setLayoutViewPath();

            return $this->render('index');
        }
    }
}