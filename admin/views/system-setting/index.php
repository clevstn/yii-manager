<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use app\models\SystemConfig as Sc;

/* @var $this \yii\web\View */
/* @var $param */
/* @var array $config 配置项：group => [code => ...] */
/* @var array $group 分组项 */

$this->title = '系统设置';
?>
<div class="panel panel-default" ng-controller="_EasyApp_SystemConfigCtrl">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" ng-click="triggerLoaded()">加载本地配置项</button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs f-13" role="tablist">
            <?php $active = ''; ?>
            <?php foreach ($group as $i => $item): ?>
                <?php if ($i == 0): $active = $item['code']; endif; ?>
                <li <?= $i == 0 ? 'class="active"' : '' ?>>
                    <a href="#<?= $item['code'] ?>" data-toggle="tab"><?= $item['name'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content">
            <!-- Tab panes -->
            <?php foreach ($config as $group => $item): ?>
                <div class="tab-pane <?= $active == $group ? 'active' : '' ?>" id="<?= $group ?>">
                    <div class="panel panel-white pt-16" style="margin-top: 22px;">
                        <div class="panel-body">
                            <form>
                                <!--表单项-->
                                <?php foreach ($item as $code => $value): ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="addon-fix"><?= $value['name'] ?></span>
                                                </div>
                                                <?php switch ($value['control']): case Sc::TEXT: ?>
                                                    <input type="text" autocomplete="off" ng-model="<?= $code ?>" class="form-control">
                                                    <?php break; case Sc::NUMBER: ?>
                                                    <input type="number" autocomplete="off" class="form-control" ng-model="<?= $code ?>" string-to-number>
                                                    <?php break; case Sc::PASSWORD: ?>
                                                    <input type="password" autocomplete="off" class="form-control" ng-model="<?= $code ?>">
                                                    <?php break; case Sc::DATETIME: ?>
                                                    <?php case Sc::DATE: ?>
                                                    <?php case Sc::YEAR: ?>
                                                    <?php case Sc::MONTH: ?>
                                                    <?php case Sc::TIME: ?>
                                                    <input id="<?= $code ?>" class="ymSysConfDates form-control" type="text" data-type="<?= $value['control'] ?>" autocomplete="off" ng-model="<?= $code ?>" readonly>
                                                    <?php break; case Sc::STATIC_: ?>
                                                    <input type="text" autocomplete="off" ng-model="<?= $code ?>" class="form-control border-y-none border-right-none box-shadow-none" readonly>
                                                    <?php break; case Sc::RANGE: ?>
                                                    <input string-to-number type="range" autocomplete="off" ng-model="<?= $code ?>" min="<?= $value['options']['min'] ?>" max="<?= $value['options']['max'] ?>" step="<?= $value['options']['step'] ?>" class="form-control p-0 border-y-none border-right-none box-shadow-none">
                                                    <?php break; case Sc::SELECT: ?>
                                                    <select ui-select2="{width:'100%'}" name="<?= $code ?>" ng-model="<?= $code ?>" id="<?= $code ?>">
                                                        <option value="">请选择</option>
                                                        <?php foreach ($value['options'] as $v => $label): ?>
                                                        <option value="<?= $v ?>"<?= $v == $value['value'] ? ' checked' : '' ?>><?= $label ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php break; case Sc::TEXTAREA: ?>
                                                    <textarea class="form-control" ng-model="<?= $code ?>" name="<?= $code ?>" id="<?= $code ?>" rows="6"></textarea>
                                                    <?php break; case Sc::FILE: ?>
                                                    <div class="form-upload-group">
                                                        <div class="inline-block">
                                                            <div class="form-upload-control">
                                                                <div class="form-upload-item" ng-click="triggerSelectImage('<?= $code ?>')">
                                                                    <i ng-hide="<?= $code ?>" class="fa fa-file-image-o f-32 text-dark"></i>
                                                                    <img ng-show="<?= $code ?>" class="form-upload-img" ng-src="{{<?= $code ?>_preview}}" alt>
                                                                    <input type="hidden" ng-model="<?= $code ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php break; case Sc::RICHTEXT: ?>
                                                    <div class="YmSysConfWangEditor" id="<?= $code ?>"></div>
                                                    <?php break; case Sc::CHECKBOX: ?>
                                                    <div lay-filter="<?= $code ?>" class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                        <?php foreach ($value['options'] as $v => $label): ?>
                                                            <input type="checkbox" name="<?= $code ?>[<?= $v ?>]" lay-filter="<?= $code ?>" value="<?= $v ?>" title="<?= $label ?>"<?= $v == $value['value'] ? ' checked' : '' ?>>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <?php break; case Sc::RADIO: ?>
                                                    <div lay-filter="<?= $code ?>" class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                        <?php foreach ($value['options'] as $v => $label): ?>
                                                        <input type="radio" name="<?= $code ?>" lay-filter="<?= $code ?>" value="<?= $v ?>" title="<?= $label ?>"<?= $v == $value['value'] ? ' checked' : '' ?>>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <?php break; case Sc::SW: ?>
                                                    <div lay-filter="<?= $code ?>" class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                        <input type="checkbox" name="<?= $code ?>" value="1" lay-filter="<?= $code ?>" lay-text="开启|关闭" lay-skin="switch">
                                                    </div>
                                                    <?php break; case Sc::CUSTOM: ?>
                                                    <!--自定义....-->
                                                    <?php break; endswitch; ?>
                                            </div>
                                            <div class="form-comment pt-16"><?= $value['tips'] ? '注：' . $value['tips'] : '' ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </form>
                        </div>
                        <div class="panel-footer clearfix">
                            <!--尾部-->
                            <div class="form-group mb-0 col-md-12">
                                <div class="addon-fix"></div>
                                <button type="button" class="btn btn-sm btn-default" ng-click="triggerResetForm('<?= $group ?>')"><?= t('reset', 'app.admin') ?></button>
                                <button type="button" class="btn btn-sm btn-primary" ng-click="triggerSubmitForm('<?= $group ?>')"><?= t('submit', 'app.admin') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
