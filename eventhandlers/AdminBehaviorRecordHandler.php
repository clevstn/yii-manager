<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\eventhandlers;

use Yii;
use yii\web\Response;
use app\models\AdminUserOperationLog as AUOLog;


/**
 * 后台行为记录处理程序
 * @author cleverstone
 * @since ym1.0
 */
class AdminBehaviorRecordHandler
{
    /**
     * @param \yii\base\ActionEvent $event
     * @throws \Exception
     */
    public static function handleClick($event)
    {
        // 查看后台是否开启行为日志记录。


        $request = Yii::$app->request;
        $response = Yii::$app->response;
        /* @var \app\builder\common\CommonController $controller */
        $controller = Yii::$app->controller;
        $route = $controller->getRoute();

        $status = AUOLog::STATUS_OK;
        $responseStr = 'HTML TEXT';
        if ($response->format === Response::FORMAT_JSON) {
            $data = $response->data;
            if (!empty($data) && isset($data['code'])) {
                $status = $data['code'] == $controller->responseSuccessCode ? AUOLog::STATUS_OK : AUOLog::STATUS_FAIL;
                $responseStr = $data['msg'];
            }
        }

        $params = $request->isGet ? $request->queryParams : $request->bodyParams;
        $requestParam = $params ? json_encode($params, JSON_UNESCAPED_UNICODE) : '';

        $opInfo = "请求参数：\n" .  $requestParam . "\n响应结果：\n" . $responseStr;

        $model = new AUOLog();
        // 该方法里的字段除事件调用写入外，其他字段必须被方法rules定义。否则无法DB写入。
        $identify = get_admin_user_identify();
        $model->setAttributes([
            'admin_user_id' => $identify ? $identify->id : 0,
            'function' => Yii::$app->rbacManager->getBehaviorsDesc($route) ?: '',
            'route' => $route,
            'ip' => $request->getUserIP() ?: '',
            'operate_status' => $status,
            'operate_info' => $opInfo,
            'client_info' => $request->userAgent ?: '',
        ]);

        if (!$model->save()) {
            // 记录失败，系统日志。
            Yii::error($model->error);
        }
    }
}