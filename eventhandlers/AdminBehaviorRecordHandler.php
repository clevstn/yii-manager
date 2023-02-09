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


        /* @var \app\builder\common\CommonController $controller */
        $controller = Yii::$app->controller;
        $route = $controller->getRoute();
        $nodeItems = Yii::$app->rbacManager->getBehaviorsDesc($route);

        if ($nodeItems) {
            $request = Yii::$app->request;

            if (
                $request->isPost
                || $request->isDelete
                || $request->isPut
                || $request->isPatch
            ) {
                $response = Yii::$app->response;

                $status = $response->statusCode == 200 ? AUOLog::STATUS_OK : AUOLog::STATUS_FAIL;
                $responseStr = $response->statusCode == 200 ? 'HTML TEXT' : $response->statusText;
                if ($response->format === Response::FORMAT_JSON) {
                    $data = $response->data;
                    if (!empty($data) && isset($data['code'])) {
                        $status = $data['code'] == $controller->responseSuccessCode ? AUOLog::STATUS_OK : AUOLog::STATUS_FAIL;
                        $responseStr = $data['msg'];
                    }
                }

                $requestMethod = $request->method;

                $queryParams = $request->queryParams;
                $queryParams = $queryParams ? json_encode($queryParams, JSON_UNESCAPED_UNICODE) : t('empty', 'app.admin');

                $bodyParams = $request->bodyParams;
                $bodyParams = $bodyParams ? json_encode($bodyParams, JSON_UNESCAPED_UNICODE) : t('empty', 'app.admin');
                $opInfo = <<<INFO
请求动作：{$requestMethod}
GET参数： {$queryParams}               
POST参数： {$bodyParams}       
响应结果： {$responseStr}                     
INFO;
                $model = new AUOLog();
                // 该方法里的字段除事件调用写入外，其他字段必须被方法rules定义。否则无法DB写入。
                $identify = get_admin_user_identify();
                $model->setAttributes([
                    'admin_user_id' => $identify ? $identify->id : 0,
                    'function' => $nodeItems['desc'],
                    'route' => $route,
                    'ip' => $request->getUserIP() ?: '',
                    'operate_status' => $status,
                    'operate_info' => $opInfo,
                    'client_info' => $request->userAgent ?: '',
                ]);
                if (!$model->save()) {
                    // 保存失败，记录系统日志。
                    system_log_error($model->error, __METHOD__);
                }
            }
        }
    }
}