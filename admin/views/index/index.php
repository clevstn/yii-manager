<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\admin\assets\IndexAsset;

/* @var $this \yii\web\View */
/* @var $param */
IndexAsset::register($this);
$this->title = '首页';
?>
<div class="panel panel-default" ng-controller="indexCtrl">
    <div class="panel-body">
        <div class="panel-body">
            <h3>统计图</h3>
        </div>
        <!-- Table -->
        <table class="table table-bordered table-hover">
            <tr>
                <th>姓名</th>
            </tr>
            <tr>
                <td>cleverstone</td>
            </tr>
        </table>
    </div>
</div>
