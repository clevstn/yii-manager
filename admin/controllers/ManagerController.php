<?php

namespace app\admin\controllers;

use app\builder\ViewBuilder;
use app\builder\form\FieldsOptions;
use app\builder\common\CommonController;

/**
 * 管理员
 * @author cleverstone
 * @since 1.0
 */
class ManagerController extends CommonController
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
    public $undetectedActions = [];

    /**
     * @return string
     * @author cleverstone
     * @since 1.0
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