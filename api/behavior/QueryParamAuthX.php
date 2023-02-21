<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\api\behavior;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 * @author cleverstone
 * @since ym1.0
 */
class QueryParamAuthX extends QueryParamAuth
{
    /**
     * {@inheritdoc}
     */
    public function handleFailure($response)
    {
        if (!empty($response->data)) {
            return;
        }

        $controller = Yii::$app->controller;
        if (!empty($controller->responseUnauthorizedCode)) {
            $code = $controller->responseUnauthorizedCode;
        } else {
            $code = 401;
        }

        /* @var Response $response */
        $response->format = Response::FORMAT_JSON;
        $response->data = [
            'code' => $code,
            'msg' => 'Your request was made with invalid credentials.',
        ];
    }
}