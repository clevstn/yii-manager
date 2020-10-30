<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

/* @var $this \yii\web\View */
/* @var $param */
$this->title = '系统设置';
?>
<div class="panel panel-default">
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#websiteconfs" aria-controls="网站配置" role="tab" data-toggle="tab">网站配置</a></li>
            <li role="presentation"><a href="#adminsconfs" aria-controls="后台配置" role="tab" data-toggle="tab">后台配置</a></li>
            <li role="presentation"><a href="#emailsconfs" aria-controls="邮箱配置" role="tab" data-toggle="tab">邮箱配置</a></li>
            <li role="presentation"><a href="#uploadsconfs" aria-controls="上传配置" role="tab" data-toggle="tab">上传配置</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="websiteconfs">
                <div class="panel-body">
                    网站配置
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="adminsconfs">
                <div class="panel-body">
                    后台配置
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="emailsconfs">
                <div class="panel-body">
                    邮箱配置
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="uploadsconfs">
                <div class="panel-body">
                    上传配置
                </div>
            </div>
        </div>
    </div>
</div>
