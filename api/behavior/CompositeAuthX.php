<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\api\behavior;

use Yii;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;

/**
 * CompositeAuth is an action filter that supports multiple authentication methods at the same time.
 *
 * The authentication methods contained by CompositeAuth are configured via [[authMethods]],
 * which is a list of supported authentication class configurations.
 *
 * The following example shows how to support three authentication methods:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'compositeAuthX' => [
 *             'class' => \app\api\behavior\CompositeAuthX::class,
 *             'authMethods' => [
 *                 \yii\filters\auth\HttpBasicAuth::class,
 *                 \yii\filters\auth\QueryParamAuth::class,
 *             ],
 *         ],
 *     ];
 * }
 * ```
 * @author cleverstone
 * @since ym1.0
 */
class CompositeAuthX extends CompositeAuth
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