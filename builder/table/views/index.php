<?php
/* @var $this \yii\web\View */
/* @var $columns array */
/* @var $page array */
/* @var $hideCheckbox boolean */
/* @var $checkboxOptions array */
?>
<div class="panel-heading border-bottom">
    <span class="f-13"><?= $this->title ?></span>
</div>
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

            <th>操作</th>
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

        </tr>
        </tbody>
    </table>
</div>

<div class="panel-body overflow-x-nowrap border-top" ng-show="ymPage" angular-ajax-page page-model="ymPage"></div>
