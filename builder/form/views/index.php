<?php
/* @var \yii\web\View $this     当前视图实例 */
/* @var array $_fields          表单字段集合 */

use app\builder\assets\NgUpload;
use app\builder\form\FieldsOptions;
use app\builder\assets\WangEditorAsset;

WangEditorAsset::register($this);
NgUpload::register($this);
?>

<div class="panel panel-default" ng-controller="formCtrl">
    <!--页面标题-->
    <?php if(!empty($this->title)): ?>
        <div class="panel-heading border-bottom clearfix">
        <span class="f-13 pull-left">
            <?= $this->title ?>
        </span>
            <div class="pull-right">
                <a class="form-header-btn" type="button" href="#" ng-click="ymFormGoBack()">
                    <i class="glyphicon glyphicon-arrow-left"></i>
                    <span>返回</span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!--表单内容-->
    <div class="panel-body border-bottom">
        <form class="row">
            <?php foreach ($_fields as $field => $options): ?>
            <?php switch ($options['control']): case FieldsOptions::CONTROL_TEXT: //文本 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <input type="text" autocomplete="off" ng-model="ymFormFields['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
            </div>
            <?php break; case FieldsOptions::CONTROL_NUMBER: // 数字 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <input type="number" autocomplete="off" ng-model="ymFormFields['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_PASSWORD: // 密码 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <input type="password" autocomplete="new-password" ng-model="ymFormFields['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_CHECKBOX: // 多选框 ?>
            <div class="form-group col-md-12">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">文本域</span>
                    </div>
                    <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="请输入文本"></textarea>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_RADIO: // 单选 ?>
            <div class="form-group col-md-12">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">单选选择控件</span>
                    </div>
                    <div class="form-control">
                        <label class="radio-inline">
                            <input type="radio" class="icheck-control" name="sex" value="1">
                            <span class="label-helper">男</span>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="icheck-control" value="2" name="sex" checked>
                            <span class="label-helper">女</span>
                        </label>
                    </div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_DATETIME: // 日期，格式：Y-m-d H:i:s ?>
            <div class="form-group col-md-12">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">多选选择控件</span>
                    </div>
                    <div class="form-control">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="icheck-control" value="1">
                            <span class="label-helper">中国</span>
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="icheck-control" value="2">
                            <span class="label-helper">美国</span>
                        </label>
                    </div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_DATE: // 日期，格式：Y-m-d ?>
            <div class="form-group col-md-12">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">富文本1</span>
                    </div>
                    <div class="YmWangEditor"></div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_YEAR: // 年，格式：Y ?>
            <?php break;case FieldsOptions::CONTROL_MONTH: // 月，格式：m ?>
            <?php break;case FieldsOptions::CONTROL_TIME: // 时，格式：H:i:s ?>
            <?php break;case FieldsOptions::CONTROL_SELECT: // 下拉选择 ?>
            <?php break;case FieldsOptions::CONTROL_HIDDEN: // 隐藏 ?>
            <?php break;case FieldsOptions::CONTROL_FILE: // 文件 ?>
            <?php break;case FieldsOptions::CONTROL_TEXTAREA: // 文本域 ?>
            <?php break;case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
            <div class="form-group col-md-12">
                <div class="input-group">
                    <div class="input-group-addon text-left">
                        <span class="addon-fix text-center">文件上传</span>
                    </div>
                    <div class="form-upload-group">
                        <div class="inline-block">
                            <div class="form-upload-control" ngf-select="ymFormUploadImage($file, 'file1')">
                                <div class="form-upload-item">
                                    <i ng-hide="file1" class="fa fa-file-image-o f-32 text-dark"></i>
                                    <img ng-show="file1" class="form-upload-img" ng-src="{{file1}}" alt>
                                </div>
                            </div>
                        </div>
                        <div class="inline-block">
                            <div class="form-upload-control" ngf-select="ymFormUploadImage($file, 'file2')">
                                <div class="form-upload-item">
                                    <i ng-hide="file2" class="fa fa-file-image-o f-32 text-dark"></i>
                                    <img ng-show="file2" class="form-upload-img" ng-src="{{file2}}" alt>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
            <?php default: // 自定义 ?>

            <?php endswitch; ?>
            <?php endforeach; ?>
            <!--表单尾部-->
            <div class="form-group col-md-12">
                <div class="addon-fix"></div>
                <button type="button" class="btn btn-sm btn-default" ng-click="ymFormResetForm()">重置</button>
                <button type="button" class="btn btn-sm btn-primary" ng-click="ymFormSubmitForm()">立即提交</button>
            </div>
        </form>
    </div>
</div>
