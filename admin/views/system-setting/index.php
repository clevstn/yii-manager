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
                                                <input type="text" autocomplete="off" class="form-control" placeholder="<?= $value['tips'] ?>">
                                            <?php break; case Sc::NUMBER: ?>
                                                <input type="number" string-to-number autocomplete="off" class="form-control" ng-model="aaa" placeholder="">
                                            <?php break; case Sc::PASSWORD: ?>
                                                <input type="password" autocomplete="off" class="form-control" placeholder="">
                                            <?php break; case Sc::DATETIME: ?>
                                            <?php case Sc::DATE: ?>
                                            <?php case Sc::YEAR: ?>
                                            <?php case Sc::MONTH: ?>
                                            <?php case Sc::TIME: ?>
                                                <input id="eeeeeee" class="ymSysConfDates form-control" type="text" data-type="<?= $value['control'] ?>" autocomplete="off" ng-model="eee" placeholder="" readonly>
                                            <?php break; case Sc::HIDDEN: ?>
                                                <input type="text" autocomplete="off" class="form-control" placeholder="" disabled>
                                            <?php break; case Sc::RANGE: ?>
                                                <input type="range" autocomplete="off" class="form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none" placeholder="">
                                            <?php break; case Sc::FILE: ?>
                                            <div class="form-upload-group">
                                                <div class="inline-block">
                                                    <div class="form-upload-control">
                                                        <div class="form-upload-item">
                                                            <i ng-hide="1" class="fa fa-file-image-o f-32 text-dark"></i>
                                                            <img ng-show="1" class="form-upload-img" ng-src="" alt>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php break; case Sc::TEXTAREA: ?>
                                                <textarea class="form-control" name="" id="" rows="6"></textarea>
                                            <?php break; case Sc::RADIO: ?>
                                                <div class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                    <input type="radio" name="sex" value="男" title="男">
                                                    <input type="radio" name="sex" value="女" title="女" checked>
                                                </div>
                                            <?php break; case Sc::CHECKBOX: ?>
                                                <div class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                    <input type="checkbox" name="like[write]" title="写作">
                                                    <input type="checkbox" name="like[read]" title="阅读" checked>
                                                    <input type="checkbox" name="like[dai]" title="发呆">
                                                </div>
                                            <?php break; case Sc::SELECT: ?>
                                                <select ui-select2="{width:'100%'}" name="cc" ng-model="ccc" id="cc">
                                                    <option value="">测试一下</option>
                                                </select>
                                            <?php break; case Sc::SW: ?>
                                                <div class="layui-form form-control pt-0 pb-0 border-y-none border-right-none box-shadow-none">
                                                    <input type="checkbox" name="switch" lay-skin="switch">
                                                </div>
                                            <?php break; case Sc::CUSTOM: ?>
                                            <?php break; endswitch; ?>
                                        </div>
                                        <div class="form-comment"><?= $value['tips'] ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!--尾部-->
                            <div class="form-group col-md-12">
                                <div class="addon-fix"></div>
                                <button type="button" class="btn btn-sm btn-default" ng-click="triggerResetForm('<?= $group ?>')"><?= t('reset', 'app.admin') ?></button>
                                <button type="button" class="btn btn-sm btn-primary" ng-click="triggerSubmitForm('<?= $group ?>')"><?= t('submit', 'app.admin') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
