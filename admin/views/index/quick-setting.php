<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

use app\admin\assets\QuickSettingAsset;

QuickSettingAsset::register($this);
/* @var $this \yii\web\View */
/* @var $param */
/* @var array $allowQuickActions 允许设置的菜单项 */
$this->title = '快捷操作设置';
?>

<div class="panel panel-white" ng-controller="_EasyApp_quickSettingCtrl">
    <table class="table table-hover">
        <tbody>
        <?php foreach ($allowQuickActions as $item): ?>
            <tr class="text-center">
                <td class="border-none">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <?php if($item['_is_action'] == 1): ?>
                                <input type="checkbox" name="quickActionItem" value="<?= $item['id'] ?>" lay-filter="inQuickItemFilter" title="加入快捷项" checked>
                            <?php else: ?>
                                <input type="checkbox" name="quickActionItem" value="<?= $item['id'] ?>" lay-filter="inQuickItemFilter" title="加入快捷项">
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td class="border-none" style="display:flex;justify-content:center;align-items:center;">
                    <div class="panel panel-white pt-6" style="width:124px;">
                        <div class="panel-body f-32 text-center text-primary pb-0">
                            <?php if(!empty($item['icon'])): ?>
                                <i class="<?= $item['icon'] ?>"></i>
                            <?php else: ?>
                                <i class="glyphicon glyphicon-briefcase"></i>
                            <?php endif; ?>
                        </div>
                        <div class="panel-body pt-6">
                            <p class="f-14 text-center">
                                <?= $item['label'] ?>
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
