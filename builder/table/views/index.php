<?php
/* @var $this \yii\web\View */
/* @var array $columns 数据列选项 */
/* @var boolean $hideCheckbox 是否隐藏第一列复选框 */
/* @var array $checkboxOptions 第一列复选框选项 */
/* @var array $rowActions 表格行操作项 */
/* @var string $modalId 模态框ID */
/* @var string $frameId 模态框中Iframe ID */

use yii\helpers\Json;
?>
<!--页面标题-->
<div class="panel-heading border-bottom">
    <span class="f-13"><?= $this->title ?></span>
</div>
<!--头部工具栏-->
<div class="panel-body border-bottom">
    <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
        <div class="btn-group btn-group-sm pull-left">
            <a href="#" type="button" class="btn btn-default">
                <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
                <span>新增</span>
            </a>
            <a href="#" type="button" class="btn btn-default" ng-click="deleteSelected()">
                <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
                <span>删除</span>
            </a>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
        <div class="btn-group btn-group-sm pull-right">
            <a href="#" type="button" class="ym_script_refresh btn btn-default">
                <i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>
                <span>刷新</span>
            </a>
            <a href="#" type="button" class="btn btn-default">
                <i class="glyphicon glyphicon-filter" aria-hidden="true"></i>
                <span>筛选</span>
            </a>
            <a href="#" type="button" class="btn btn-default">
                <i class="glyphicon glyphicon-export" aria-hidden="true"></i>
                <span>导出</span>
            </a>
        </div>
    </div>
</div>
<div class="panel-body overflow-x">
    <!--表格-->
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
            <td ng-bind="value['<?= $field ?>']" style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>></td>
            <?php endforeach; ?>

        </tr>
        </tbody>
    </table>
</div>

<!--分页-->
<div class="panel-body border-top" ng-show="ymPage" angular-ajax-page page-model="ymPage"></div>

<!--Modal-->
<?php
\yii\bootstrap\Modal::begin([
    'id' => $modalId,
    'closeButton' => false,
]);
?>

<iframe scrolling="auto" frameborder="0" allowtransparency="true" id="<?= $frameId ?>" style="width:100%;height:100%;"></iframe>

<?php
\yii\bootstrap\Modal::end();
?>
