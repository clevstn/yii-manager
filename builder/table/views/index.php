<?php
/* @var $this \yii\web\View */
/* @var array $columns 数据列选项 */
/* @var boolean $hideCheckbox 是否隐藏复选框 */
/* @var array $checkboxOptions 复选框选项 */
/* @var array $rowActions 行操作项 */
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

            <!--渲染表头-->
            <?php foreach ($columns as $item): ?>
                <th style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>>
                    <?= $item['title'] ?>
                </th>
            <?php endforeach; ?>

            <!--操作项渲染-->
            <?php if (!empty($rowActions)): ?>
            <th>操作</th>
            <?php endif; ?>

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

            <!--渲染列表-->
            <?php foreach ($columns as $field => $item): ?>
            <td ng-bind="value['<?= $field ?>']" style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>></td>
            <?php endforeach; ?>

            <!--操作项渲染-->
            <?php if (!empty($rowActions)): ?>
            <td class="row-handle">
                <div class="dropdown">
                    <a href="#" type="button" class="btn btn-sm btn-default dropdown-toggle"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        操作
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">编辑</a></li>
                        <li><a href="#">删除</a></li>
                        <li><a href="#">详情</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">更多操作</a></li>
                    </ul>
                </div>
            </td>
            <?php endif; ?>

        </tr>
        </tbody>
    </table>
</div>

<!--分页-->
<div class="panel-body overflow-x-nowrap border-top" ng-show="ymPage" angular-ajax-page page-model="ymPage"></div>