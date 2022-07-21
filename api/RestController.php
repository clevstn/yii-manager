<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\api;

use yii\web\Response;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use app\builder\traits\Http;
use yii\filters\ContentNegotiator;
use yii\filters\auth\CompositeAuth;

/**
 * 接口继承类
 * @author cleverstone
 * @since 1.0
 */
abstract class RestController extends Controller
{
    use Http;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        // 直接覆盖父级行为定义, 便于更改
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'text/html' => Response::FORMAT_HTML,
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ];
    }
}