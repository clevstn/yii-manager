<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\components\Uploads;
use app\extend\Extend;
use app\models\AreaCode;
use app\models\AuthGroups;
use Yii;
use app\models\AdminUser;
use app\builder\common\CommonController;
use app\builder\form\FieldsOptions;
use app\builder\ViewBuilder;

/**
 * 个人中心
 * @author cleverstone
 * @since ym1.0
 */
class HomeController extends CommonController
{
    /**
     * 访问动作设置
     * @var array
     */
    public $actionVerbs = [
        'index' => ['get', 'post'],
        'bind' => ['get'],
    ];

    /**
     * @var array 过滤掉RBAC的action id
     */
    public $undetectedActions = ['index', 'bind'];

    /**
     * 个人设置
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function actionIndex()
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;
        if ($this->isGet) {
            $form = ViewBuilder::form();
            $form->autoBack = false;
            $form->title = '个人设置';
            $form->fields = [
                'id' => form_fields_helper(FieldsOptions::CONTROL_HIDDEN, [
                    'default' => $identify->getId(),
                ]),
                'username' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '用户名',
                    'default' => $identify->username,
                    'attribute' => ['readonly' => true],
                    'layouts' => '8',
                ]),
                'password' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                    'label' => '密码',
                    'placeholder' => '请输入新密码',
                    'required' => false,
                    'layouts' => '8',
                ]),
                'repassword' => form_fields_helper(FieldsOptions::CONTROL_PASSWORD, [
                    'label' => '确认密码',
                    'placeholder' => '请确认密码',
                    'required' => false,
                    'layouts' => '8',
                ]),
                'email' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '邮箱',
                    'placeholder' => '请填写邮箱',
                    'default' => $identify->email,
                    'layouts' => '8',
                ]),
                'photo' => form_fields_helper(FieldsOptions::CONTROL_FILE, [
                    'label' => '头像',
                    'placeholder' => '请上传头像',
                    'default' => $identify->photo,
                    'defaultLink' => attach_url($identify->photo),
                    'required' => false,
                    'comment' => '管理员头像，用于展示',
                    'number' => 1,
                    'fileScenario' => Uploads::SCENARIO_IMAGE,
                    'saveDirectory' => 'admin',
                    'pathPrefix' => '666666',
                    'layouts' => '8',
                ]),
                'an' => form_fields_helper(FieldsOptions::CONTROL_SELECT, [
                    'label' => '区号',
                    'placeholder' => '请选择区号',
                    'default' => $identify->an,
                    'options' => AreaCode::areaCodes(AreaCode::STATUS_NORMAL),
                    'layouts' => '8',
                ]),
                'mobile' => form_fields_helper(FieldsOptions::CONTROL_NUMBER, [
                    'label' => '手机号',
                    'placeholder' => '请填写手机号',
                    'default' => $identify->mobile,
                    'layouts' => '8',
                ]),
                'group' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '管理组',
                    'default' => AuthGroups::getGroupName($identify->group),
                    'attribute' => ['readonly' => true],
                    'layouts' => '8',
                ]),
                'created_at' => form_fields_helper(FieldsOptions::CONTROL_TEXT, [
                    'label' => '注册时间/修改时间',
                    'default' => $identify->created_at . '  /  ' . $identify->updated_at,
                    'attribute' => ['readonly' => true],
                    'required' => false,
                    'layouts' => '8',
                ]),
            ];

            return $form->render($this);
        } else {
            $bodyParams = $this->post;
            // 设置验证场景
            $identify->setScenario('edit');
            // 数据校验
            if ($identify->load($bodyParams)) {
                if ($identify->save()) {
                    return $this->asSuccess(t('submitted successfully', 'app.admin'));
                }

                return $this->asFail($identify->error);
            }

            return $this->asFail(t('request parameter loading failed', 'app.admin'));
        }
    }

    /**
     * MFA绑定
     * @return string
     */
    public function actionBind()
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;

        $accountName = Yii::$app->params['admin_title_en'] . ':' . $identify->username;
        $qrcodeUrl = Extend::googleAuth()->getQRCodeGoogleUrl(
            $accountName,
            $identify->google_key,
            Yii::$app->params['admin_title']
        );

        return $this->render('mfa_bind', [
            'titleName' => 'MFA绑定',
            'qrcodeUrl' => $qrcodeUrl,
            'accountName' => $accountName,
            'googleKey' => $identify->google_key,
        ]);
    }
}