<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\api\controllers;

use yii\web\Response;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\RateLimiter;
use app\builder\traits\Http;
use yii\filters\ContentNegotiator;
use yii\filters\auth\CompositeAuth;

/**
 * 接口继承类
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class RestController extends Controller
{
    use Http;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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