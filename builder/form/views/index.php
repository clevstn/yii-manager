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
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <div class="form-control">
                        <?php foreach ($options['options'] as $title => $value): ?>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="icheck-control" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> value="<?= $value ?>">
                            <span class="label-helper"><?= $title ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_RADIO: // 单选 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <div class="form-control">
                        <?php foreach ($options['options'] as $title => $value): ?>
                        <label class="radio-inline">
                            <input type="radio" class="icheck-control" name="ymFormFields<?= $field ?>" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> value="<?= $value ?>">
                            <span class="label-helper"><?= $title ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_DATETIME: // 日期，格式：Y-m-d H:i:s ?>
            <?php case FieldsOptions::CONTROL_DATE: // 日期，格式：Y-m-d ?>
            <?php case FieldsOptions::CONTROL_YEAR: // 年，格式：Y ?>
            <?php case FieldsOptions::CONTROL_MONTH: // 月，格式：m ?>
            <?php case FieldsOptions::CONTROL_TIME: // 时，格式：H:i:s ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <input id="ymFormDate_<?= $field ?>" class="ymFormDates form-control" type="text" data-type="<?= $options['control'] ?>" data-range="<?= $options['range'] ?>" autocomplete="off" ng-model="ymFormFields['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> placeholder="<?= $options['placeholder'] ?>" readonly>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_SELECT: // 下拉选择 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <select id="ymFormSelect2_<?= $field ?>"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" ui-select2="{width:'100%'}" ng-model="ymFormFields['<?= $field ?>']" data-placeholder="<?= $options['placeholder'] ?>">
                        <option value=""><?= $options['placeholder'] ?></option>
                        <?php foreach ($options['options'] as $title => $value): ?>
                            <option value="<?= $value ?>"><?= $title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_HIDDEN: // 隐藏 ?>
            <input type="hidden" ng-model="ymFormFields['<?= $field ?>']">
            <?php break;case FieldsOptions::CONTROL_TEXTAREA: // 文本域 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <textarea ng-model="ymFormFields['<?= $field ?>']" rows="<?= $options['rows'] ?>" class="form-control" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> placeholder="<?= $options['placeholder'] ?>"></textarea>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix"><?= $options['label'] ?></span>
                    </div>
                    <div class="YmWangEditor" id="ymFormRichtext_<?= $field ?>"></div>
                </div>
            </div>
            <?php break;case FieldsOptions::CONTROL_FILE: // 文件 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon text-left">
                        <span class="addon-fix text-center"><?= $options['label'] ?></span>
                    </div>
                    <div class="form-upload-group">
                        <?php for ($i = 0; $i < $options['number']; $i++): ?>
                        <div class="inline-block">
                            <div class="form-upload-control" ngf-select="ymFormUploadImage($file, 'ymFormFileLink<?= $field . $i ?>')">
                                <div class="form-upload-item">
                                    <i ng-hide="ymFormFileLink<?= $field . $i ?>" class="fa fa-file-image-o f-32 text-dark"></i>
                                    <img ng-show="ymFormFileLink<?= $field . $i ?>" class="form-upload-img" ng-src="{{ymFormFileLink<?= $field . $i ?>}}" alt>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
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
