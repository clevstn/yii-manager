<?php
/**
 * @apiDefine error500 500响应
 * 状态码为500的JSON响应
 *
 * @apiError {Number} code=500 业务码
 * @apiError {String} msg=业务错误 业务消息
 * @apiErrorExample 500响应
 * {
 *      "code":500,
 *      "msg":"业务错误"
 * }
 */
function error500(){};

/**
 * @apiDefine error401 401响应
 * 状态码为401的JSON响应
 *
 * @apiError {Number} code=401 业务码
 * @apiError {String} msg=账号未授权,不允许访问 业务消息
 * @apiErrorExample 401响应
 * {
 *      "code":401
 *      "msg":"账号未授权,不允许访问."
 * }
 */
function error401(){};

/**
 * @apiDefine error500_401 500-401响应
 * 状态码为500和401的JSON响应
 *
 * @apiError {Number} code=500 业务码
 * @apiError {String} msg=业务错误 业务消息
 * @apiErrorExample 500响应
 * {
 *      "code":500,
 *      "msg":"业务错误"
 * }
 * @apiError {Number} code=401 业务码
 * @apiError {String} msg=账号未授权,不允许访问 业务详细
 * @apiErrorExample 401响应
 * {
 *      "code":401
 *      "msg":"账号未授权,不允许访问."
 * }
 */
function error500_401(){};

/**
 * @apiDefine common_header 公共请求头
 * 公共请求头部数据
 *
 * @apiHeader {String} X-Requested-With AJAX请求标识
 * @apiHeaderExample 请求头示例
 * {
 *      "X-Requested-With":"XMLHttpRequest",
 * }
 */
function common_header(){};

/**
 * @apiDefine user_access 授权访问
 * 该接口只能授权用户访问
 */

function user_access(){};