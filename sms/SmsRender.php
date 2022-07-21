<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\sms;

use Yii;
use yii\base\BaseObject;

/**
 * 短信发送器
 * @author cleverstone
 * @since 1.0
 */
class SmsRender extends BaseObject
{
    /**
     * @var string 短信模板根路径
     */
    public $viewRootPath = __DIR__ . '/views';

    /**
     * @var string 模板名称,默认为[[default]]
     */
    public $viewName = 'default';

    /**
     * @var array 内容参数
     * - use string 短信用途,必传项
     */
    public $params = [];

    /**
     * @var null|string 错误信息
     */
    public $error;

    /**
     * 执行模板渲染
     * @return false|string
     * @throws \Throwable
     */
    public function execute()
    {
        // 获取模板根路径
        $absoluteViewRootPath = realpath($this->viewRootPath);
        if (!$absoluteViewRootPath) {
            $this->error = t('error message template root path');
            return false;
        }

        // 检查模板是否存在
        $tplPath = rtrim($absoluteViewRootPath, '/') . '/' . $this->viewName . '.php';
        if (!is_file($tplPath)) {
            $this->error = t('the SMS template does not exist');
            return false;
        }

        // 填充模板参数
        return Yii::$app->view->renderPhpFile($tplPath, $this->params);
    }
}