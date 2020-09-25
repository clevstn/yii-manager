<?php
/* @var $this \yii\web\View     当前视图实例 */

use app\builder\assets\WangEditorAsset;
use app\builder\assets\NgUpload;

WangEditorAsset::register($this);
NgUpload::register($this);
?>

<div class="panel panel-default" ng-controller="formCtrl">
    <!--页面标题-->
    <?php if(!empty($this->title)): ?>
        <div class="panel-heading border-bottom clearfix">
        <span class="f-13 pull-left">
            <?= $this->title ?>
        </span>
            <div class="pull-right">
                <a class="form-header-btn" type="button" href="#" ng-click="ymFormGoBack()">
                    <i class="glyphicon glyphicon-arrow-left"></i>
                    <span>返回</span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!--表单内容-->
    <div class="panel-body border-bottom">
        <form class="row">

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">邮箱</span>
                    </div>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="请输入邮箱">
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">密码</span>
                    </div>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入密码">
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon text-left">
                        <span class="addon-fix text-center">文件上传</span>
                    </div>
                    <div class="form-control" ngf-select="YmFormUploadImage($file)" ngf-pattern="'image/*'" ngf-accept="'image/*'" ngf-max-size="20MB">
                        Select
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">文本域</span>
                    </div>
                    <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="请输入文本"></textarea>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">单选选择控件</span>
                    </div>
                    <div class="form-control">
                        <label class="radio-inline">
                            <input type="radio" class="icheck-control" name="sex" value="1">
                            <span class="label-helper">男</span>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="icheck-control" value="2" name="sex" checked>
                            <span class="label-helper">女</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">多选选择控件</span>
                    </div>
                    <div class="form-control">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="icheck-control" value="1">
                            <span class="label-helper">中国</span>
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="icheck-control" value="2">
                            <span class="label-helper">美国</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">富文本1</span>
                    </div>
                    <div class="YmWangEditor"></div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="addon-fix">富文本2</span>
                    </div>
                    <div class="YmWangEditor"></div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="addon-fix"></div>
                <button type="button" class="btn btn-sm btn-default" ng-click="ymFormResetForm()">重置</button>
                <button type="button" class="btn btn-sm btn-primary" ng-click="ymFormSubmitForm()">立即提交</button>
            </div>

        </form>
    </div>
</div>
