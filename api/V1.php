<?php
// +----------------------------------------------------------------------
// | API模块version 1
// +----------------------------------------------------------------------
// | 日期：2020/8/4
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\api;

class V1 extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\controllers\v1';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->setViewPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'v1');
        // custom initialization code goes here
    }
}
