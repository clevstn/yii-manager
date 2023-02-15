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
<div class="panel panel-default">
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

        <!-- Tab panes -->
        <div class="tab-content">
            <?php foreach ($config as $group => $item): ?>
                <div class="tab-pane <?= $active == $group ? 'active' : '' ?>" id="<?= $group ?>">
                    <div class="panel-body">
                        <form>
                            <?php foreach ($item as $code => $value): ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="addon-fix">测试</span>
                                            </div>
                                            <?php switch ($value['control']): case Sc::TEXT: ?>
                                            <input type="text" autocomplete="off" class="form-control" placeholder="<?= $value['tips'] ?>">
                                            <?php break; case Sc::NUMBER: ?>
                                             <input type="text" autocomplete="off" class="form-control" placeholder="123456">
                                            <?php break; case Sc::PASSWORD: ?>
                                            <?php break; case Sc::TIME: ?>
                                            <?php break; case Sc::DATE: ?>
                                            <?php break; case Sc::HIDDEN: ?>
                                            <?php break; case Sc::RANGE: ?>
                                            <?php break; case Sc::FILE: ?>
                                            <?php break; case Sc::TEXTAREA: ?>
                                            <?php break; case Sc::RADIO: ?>
                                            <?php break; case Sc::CHECKBOX: ?>
                                            <?php break; case Sc::SELECT: ?>
                                            <?php break; case Sc::MULTIPLE: ?>
                                            <?php break; case Sc::SW: ?>
                                            <?php break; case Sc::CUSTOM: ?>
                                            <?php break; endswitch; ?>
                                        </div>
                                        <div class="form-comment">注: 测试一下 ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="form-group col-md-12">
                                <div class="addon-fix"></div>
                                <button type="button" class="btn btn-sm btn-primary" ng-click="triggerSubmitForm()"><?= t('submit', 'app.admin') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
