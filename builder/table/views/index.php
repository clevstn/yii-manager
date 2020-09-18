<?php
/* @var $this \yii\web\View */
/* @var array $columns 数据列选项 */
/* @var boolean $hideCheckbox 是否隐藏第一列复选框 */
/* @var array $checkboxOptions 第一列复选框选项 */
/* @var array $rowActions 表格行操作项 */
/* @var array $widgets 切点处要加入组件 */
/* @var array $toolbars 工具栏操作项 */

use yii\helpers\Json;
use app\builder\table\Table;
?>
<!--页面标题-->
<?php if(!empty($this->title)): ?>
<div class="panel-heading border-bottom">
    <span class="f-13"><?= $this->title ?></span>
</div>
<?php endif; ?>

<!--工具栏开始-->
<?php Table::beginTableTool($widgets); ?>

<!--头部工具栏-->
<?php if (!empty($toolbars)): ?>
<div class="panel-body border-bottom">

    <!--工具栏左-->
    <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
        <?php if (!empty($toolbars['left'])): ?>
        <div class="btn-group btn-group-sm pull-left">
            <!--自定义-->
            <?php foreach ($toolbars['left'] as $item): ?>
            <a href="#" type="button" class="btn btn-default" ng-click="customMethod()">
                <i class="<?= !empty($item['icon']) ? $item['icon'] : '' ?>" aria-hidden="true"></i>
                <span><?= !empty($item['title']) ? $item['title'] : '' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!--工具栏右-->
    <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
        <?php if (!empty($toolbars['right'])): ?>
        <div class="btn-group btn-group-sm pull-right">
            <?php foreach ($toolbars['right'] as $item): ?>
                <?php switch ($item['type']): case 'refresh': ?>
                    <!--刷新-->
                    <a href="#" type="button" class="ym_script_refresh btn btn-default">
                        <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-refresh' ?>" aria-hidden="true"></i>
                        <span><?= !empty($item['title']) ? $item['title'] : '刷新' ?></span>
                    </a>
                    <?php break; case 'filter': ?>
                    <!--筛选-->
                    <a href="#" type="button" class="btn btn-default">
                        <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-filter' ?>" aria-hidden="true"></i>
                        <span><?= !empty($item['title']) ? $item['title'] : '筛选' ?></span>
                    </a>
                    <?php break; case 'export': ?>
                    <!--导出-->
                    <a href="#" type="button" class="btn btn-default">
                        <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-export' ?>" aria-hidden="true"></i>
                        <span><?= !empty($item['title']) ? $item['title'] : '导出' ?></span>
                    </a>
                    <?php break; default: ?>
                    <!--自定义-->
                    <a href="#" type="button" class="btn btn-default" ng-click="customMethod()">
                        <i class="<?= !empty($item['icon']) ? $item['icon'] : '' ?>" aria-hidden="true"></i>
                        <span><?= !empty($item['title']) ? $item['title'] : '' ?></span>
                    </a>
                <?php endswitch; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php endif; ?>

<!--工具栏结束-->
<?php Table::endTableTool($widgets); ?>

<!--表格-->
<div class="panel-body overflow-x">
    <table class="table table-bordered table-hover">
        <thead>
        <tr class="bg-light">

            <!--隐藏多选框-->
            <?php if(!$hideCheckbox): ?>
            <th style="<?= $checkboxOptions['style'] ?>"<?= $checkboxOptions['attribute'] ?>>
                <label for="th_0"></label>
                <input type="checkbox" id="th_0" class="tableCheckboxTool hidden">
            </th>
            <?php endif; ?>

            <!--操作项渲染-->
            <?php if (!empty($rowActions)): ?>
                <th style="width:50px;">操作</th>
            <?php endif; ?>

            <!--渲染表头-->
            <?php foreach ($columns as $item): ?>
                <th style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>>
                    <?= $item['title'] ?>
                </th>
            <?php endforeach; ?>

        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="(key, value) in list track by key" on-finish-render="ev-repeat-finished">

            <!--隐藏多选框-->
            <?php if(!$hideCheckbox): ?>
            <td style="<?= $checkboxOptions['style'] ?>"<?= $checkboxOptions['attribute'] ?>>
                <label for="td_{{key}}"></label>
                <input type="checkbox" id="td_{{key}}" class="tableCheckbox hidden" value="{{value}}">
            </td>
            <?php endif; ?>

            <!--操作项渲染-->
            <?php if (!empty($rowActions)): ?>
                <td class="row-handle" style="width:50px;">
                    <div class="dropdown">
                        <a href="#" type="button" class="btn btn-sm btn-default dropdown-toggle"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            操作
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!--遍历设置操作项-->
                            <?php foreach ($rowActions as $actionItem): ?>
                                <?php switch ($actionItem['type']): case 'division': ?>
                                    <li role="separator" class="divider"></li>
                                    <?php break; default: ?>
                                    <li>
                                        <a href="#" ng-click="rowActions(value, '<?= html_escape(Json::encode($actionItem)) ?>')">
                                            <i class="actions-icon <?= $actionItem['options']['icon'] ?>"></i>
                                            <?= html_escape($actionItem['options']['title']) ?>
                                        </a>
                                    </li>
                                <?php endswitch; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </td>
            <?php endif; ?>

            <!--渲染列表-->
            <?php foreach ($columns as $field => $item): ?>
            <td style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>>
                <span ng-bind-html="value['<?= $field ?>'] | toHtml"></span>
            </td>
            <?php endforeach; ?>

        </tr>
        </tbody>
    </table>
</div>

<!--分页开始-->
<?php Table::beginTablePage($widgets); ?>

<!--分页-->
<div class="panel-body border-top" ng-show="ymPage" angular-ajax-page page-model="ymPage"></div>

<!--分页结束-->
<?php Table::endTablePage($widgets); ?>
