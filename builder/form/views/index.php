<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\builder\form\FieldsOptions;

/* @var \yii\web\View $this     当前视图实例 */
/* @var array $_fields          表单字段集合 */
/* @var boolean $_backBtn       是否设置返回按钮 */
?>

<div class="panel panel-default" ng-controller="_EasyApp_FormCtrl">
    <!--页面标题-->
    <?php if (!empty($this->title) || $_backBtn): ?>
    <div class="panel-heading border-bottom clearfix">
        <?php if(!empty($this->title)): ?>
        <span class="f-13 pull-left">
            <?= $this->title ?>
        </span>
        <?php endif; ?>
        
        <?php if ($_backBtn): ?>
        <div class="pull-right">
            <a class="form-header-btn" type="button" href="#" ng-click="triggerGoBack()">
                <i class="glyphicon glyphicon-arrow-left"></i>
                <span><?= t('back', 'app.admin') ?></span>
            </a>
        </div>
        <?php endif; ?>
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
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                            <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <input type="text" autocomplete="off" ng-model="formFieldsData['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
                <?php if (!empty($options['comment'])): ?>
                <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break; case FieldsOptions::CONTROL_NUMBER: // 数字 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <input type="number" autocomplete="off" string-to-number ng-model="formFieldsData['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_PASSWORD: // 密码 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <input type="password" autocomplete="new-password" ng-model="formFieldsData['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> class="form-control" placeholder="<?= $options['placeholder'] ?>">
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_CHECKBOX: // 多选框 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <div class="form-control">
                        <?php foreach ($options['options'] as $value => $label): ?>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="ymFormCheckbox_<?= $field ?> icheck-control" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> value="<?= $value ?>">
                            <span class="label-helper"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_RADIO: // 单选 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <div class="form-control">
                        <?php foreach ($options['options'] as $value => $label): ?>
                        <label class="radio-inline">
                            <input type="radio" class="ymFormRadio_<?= $field ?> icheck-control" name="ymFormFields<?= $field ?>" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> value="<?= $value ?>">
                            <span class="label-helper"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_DATETIME: // 日期，格式：Y-m-d H:i:s ?>
            <?php case FieldsOptions::CONTROL_DATE: // 日期，格式：Y-m-d ?>
            <?php case FieldsOptions::CONTROL_YEAR: // 年，格式：Y ?>
            <?php case FieldsOptions::CONTROL_MONTH: // 月，格式：m ?>
            <?php case FieldsOptions::CONTROL_TIME: // 时，格式：H:i:s ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <input id="ymFormDate_<?= $field ?>" class="ymFormDates form-control" type="text" data-type="<?= $options['control'] ?>" data-range="<?= $options['range'] ?>" autocomplete="off" ng-model="formFieldsData['<?= $field ?>']" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> placeholder="<?= $options['placeholder'] ?>" readonly>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_SELECT: // 下拉选择 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <select id="ymFormSelect2_<?= $field ?>"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" ui-select2="{width:'100%'}" ng-model="formFieldsData['<?= $field ?>']" data-placeholder="<?= $options['placeholder'] ?>">
                        <option value=""><?= $options['placeholder'] ?></option>
                        <?php foreach ($options['options'] as $value => $label): ?>
                            <option value="<?= $value ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_HIDDEN: // 隐藏 ?>
            <input type="hidden" ng-model="formFieldsData['<?= $field ?>']">
            <?php break;case FieldsOptions::CONTROL_TEXTAREA: // 文本域 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <textarea ng-model="formFieldsData['<?= $field ?>']" rows="<?= $options['rows'] ?>" class="form-control" style="<?= $options['style'] ?>"<?= $options['attribute'] ?> placeholder="<?= $options['placeholder'] ?>"></textarea>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_RICHTEXT: // 富文本 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <div class="YmFormWangEditor" id="ymFormRichtext_<?= $field ?>"></div>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_FILE: // 文件 ?>
            <div class="form-group col-md-<?= $options['layouts'] ?>">
                <div class="input-group">
                    <div class="input-group-addon text-left">
                        <span class="addon-fix text-center">
                            <?php if ($options['required']): ?>
                                <sup class="sup text-red">*</sup>
                            <?php endif; ?>
                            <?= $options['label'] ?>
                        </span>
                    </div>
                    <div class="form-upload-group">
                        <?php for ($i = 0; $i < $options['number']; $i++): ?>
                        <div class="inline-block">
                            <div class="form-upload-control" ng-click="triggerSelectImage($file, '<?= $options["fileType"] ?>', '<?= $options["saveDirectory"] ?>', '<?= $options["pathPrefix"] ?>', '<?= $options["fileScenario"] ?>', '<?= $field ?>', <?= $i ?>)">
                                <div class="form-upload-item">
                                    <i ng-hide="formFileLink<?= $field . $i ?>" class="fa fa-file-image-o f-32 text-dark"></i>
                                    <img ng-show="formFileLink<?= $field . $i ?>" class="form-upload-img" ng-src="{{formFileLink<?= $field . $i ?>}}" alt>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                        <input type="hidden" ng-model="formFieldsData['<?= $field ?>']">
                    </div>
                </div>
                <?php if (!empty($options['comment'])): ?>
                    <div class="form-comment"><?= t('note', 'app.admin') ?>: <?= $options['comment'] ?></div>
                <?php endif; ?>
            </div>
            <?php break;case FieldsOptions::CONTROL_CUSTOM: // 自定义 ?>
            <?= $options['widget']->render() ?>
            <?php break; ?>
            <?php endswitch; ?>
            <?php endforeach; ?>
            <!--表单尾部-->
            <div class="form-group col-md-12">
                <div class="addon-fix"></div>
                <button type="button" class="btn btn-sm btn-default" ng-click="triggerClearForm()"><?= t('clear', 'app.admin') ?></button>
                <button type="button" class="btn btn-sm btn-default" ng-click="triggerResetForm()"><?= t('reset', 'app.admin') ?></button>
                <button type="button" class="btn btn-sm btn-primary" ng-click="triggerSubmitForm()"><?= t('submit', 'app.admin') ?></button>
            </div>
        </form>
    </div>
</div>
