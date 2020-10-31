<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\traits;

use yii\web\Request;
use yii\web\Response;

/**
 * HTTP常用方法
 * @property array $get             Verb `get` params
 * @property array $post            Verb `post` params
 * @property boolean $isGet         Is verb `get` params
 * @property boolean $isPost        Is verb `post` params
 * @property boolean $isAjax        Is ajax request params
 * @property string|null $domain    Current request domain
 * @property Request $request
 * @property Response $response
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
trait Http
{

    /**
     * @var bool By call this attributes `get` `post`, this is effective.
     */
    public $emptyStrToNull = true;

    /**
     * Get verb `get` info
     * @return array
     */
    public function getGet()
    {
        $get = $this->request->get();
        if (!empty($get)) {
            if (true === $this->emptyStrToNull) {
                foreach ($get as $queryStr => &$value) {
                    $value = $value === ''  ? null : $value;
                }
            }

            return $get;
        }

        return [];
    }

    /**
     * Get verb `post` info
     * @return array
     */
    public function getPost()
    {
        $post = $this->request->post();
        if (!empty($post)) {
            if (true === $this->emptyStrToNull) {
                foreach ($post as $bodyStr => &$value) {
                    $value = $value === '' ? null : $value;
                }
            }

            return $post;
        }

        return [];
    }

    /**
     * Detect get
     * @return bool
     */
    public function getIsGet()
    {
        return $this->request->isGet;
    }

    /**
     * Detect post
     * @return bool
     */
    public function getIsPost()
    {
        return $this->request->isPost;
    }

    /**
     * Detect ajax
     * @param boolean $general 是否泛指
     * @return bool
     */
    public function getIsAjax($general = true)
    {
        return $general ? ($this->request->getIsAjax() || accept_json()) : $this->request->getIsAjax();
    }

    /**
     * Get domain
     * @return string|null
     */
    public function getDomain()
    {
        return $this->request->hostInfo;
    }

    /**
     * Json成功响应
     * @param string $msg
     * @param array $data
     * @return string
     */
    public function asSuccess($msg = '执行成功', $data = [])
    {
        $code = 200;
        return $this->asJson(compact('code', 'data', 'msg'));
    }

    /**
     * Json失败响应
     * @param string $msg
     * @return string
     */
    public function asFail($msg = '执行失败')
    {
        $code = 500;
        return $this->asJson(compact('code', 'msg'));
    }

    /**
     * Json无权限响应
     * @param string $msg
     * @return string
     */
    public function asUnauthorized($msg = '您没有权限访问')
    {
        $code = 401;
        return $this->asJson(compact('code', 'msg'));
    }
}