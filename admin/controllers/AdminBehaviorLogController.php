<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\admin\controllers;

use app\builder\ViewBuilder;
use app\builder\form\FieldsOptions;
use app\builder\common\CommonController;

/**
 * 管理员操作日志
 * @author HiLi
 * @since 1.0
 */
class AdminBehaviorLogController extends CommonController
{
    /**
     * {@inheritDoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 管理员操作日志列表
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