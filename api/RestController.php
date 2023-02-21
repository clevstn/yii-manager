<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\api;

use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use app\builder\traits\Http;
use app\api\behavior\CompositeAuthX;
use app\api\behavior\QueryParamAuthX;

/**
 * 接口继承类
 * @author cleverstone
 * @since ym1.0
 */
abstract class RestController extends Controller
{
    use Http;

    /**
     * 请求动作定义
     * @var array Verbs to specify the actions.
     */
    public $actionVerbs = [];

    /**
     * 游客可以访问的action-id。
     * @var array Define actions that don't require authorization.
     */
    public $guestActions = [];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        // 直接覆盖父级行为定义, 便于更改
        return [
            /*'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'text/html' => Response::FORMAT_HTML,
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],*/
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->actionVerbs,
            ],
            'authenticator' => [
                'class' => CompositeAuthX::className(),
                'user' => Yii::$app->apiUser,
                'optional' => $this->guestActions,
                'authMethods' => [
                    'queryParamAuth' => [
                        'class' => QueryParamAuthX::class,
                        'tokenParam' => '_access_token',
                    ],
                ],
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ];
    }
}