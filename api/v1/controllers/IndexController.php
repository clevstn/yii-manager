<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\api\v1\controllers;

use app\api\RestController;

/**
 * 默认接口
 * @author cleverstone
 * @since ym1.0
 */
class IndexController extends RestController
{
    public $guestActions = ['index'];

    /**
     * @api {GET} /v1/:- 默认请求接口
     * @apiDescription
     * <a target="_blank" href="#">
     * 在wiki中查看该接口
     * </a>
     * @apiVersion 1.0.0
     * @apiGroup Other
     *
     * @apiUse common_header
     *
     * @apiParam {String} - -
     *
     * @apiSuccess {Number} code 业务码
     * @apiSuccess {String} msg 业务消息
     * @apiSuccess {Number} data[-] -
     * @apiSuccessExample 200响应
     * {
     *      "code":200,
     *      "msg":"请求成功",
     *      "data": {
     *          //...
     *      }
     * }
     * @apiUse error500
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'version' => $this->module->version,
        ]);
    }
}
