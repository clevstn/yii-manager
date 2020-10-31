<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use app\builder\ViewBuilder;
use app\builder\form\FieldsOptions;
use app\builder\common\CommonController;

/**
 * 管理员操作日志
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AdminBehaviorLogController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'index',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'index',
    ];

    /**
     * this is index page
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $form = ViewBuilder::form();
        $form->title = '管理员';
        $form->backBtn = false;
        $form->autoBack = false;
        $form->fields = [
            'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                'label' => '用户名',
                'placeholder' => '请填写用户名',
            ]),
        ];

        return $form->render($this);
    }
}