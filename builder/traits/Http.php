<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/12
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\traits;

use yii\web\Request;
use yii\web\Response;

/**
 * HTTP常用方法
 *
 * @property array $get             Verb `get` params
 * @property array $post            Verb `post` params
 * @property boolean $isGet         Is verb `get` params
 * @property boolean $isPost        Is verb `post` params
 * @property boolean $isAjax        Is ajax request params
 * @property string|null $domain    Current request domain
 *
 * @property Request $request
 * @property Response $response
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
trait Http
{

    /**
     * By call this attributes `get` `post`, this is effective.
     *
     * @var bool
     */
    public $emptyStrToNull = true;

    /**
     * Get verb `get` info
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getGet()
    {
        $get = $this->request->get();
        if (!empty($get)) {
            foreach ($get as $queryStr => &$value) {
                $value = $value === '' && true === $this->emptyStrToNull ? null : $value;
            }

            return $get;
        }

        return [];
    }

    /**
     * Get verb `post` info
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getPost()
    {
        $post = $this->request->post();
        if (!empty($post)) {
            foreach ($post as $bodyStr => &$value) {
                $value = $value === '' && true === $this->emptyStrToNull ? null : $value;
            }

            return $post;
        }

        return [];
    }

    /**
     * Detect get
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsGet()
    {
        return $this->request->isGet;
    }

    /**
     * Detect post
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsPost()
    {
        return $this->request->isPost;
    }

    /**
     * Detect ajax
     *
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getIsAjax()
    {
        return $this->request->getIsAjax();
    }

    /**
     * Get domain
     *
     * @return string|null
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function getDomain()
    {
        return $this->request->hostInfo;
    }
}