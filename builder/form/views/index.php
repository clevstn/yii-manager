<?php
/* @var $this \yii\web\View     当前视图实例 */
?>
<!--页面标题-->
<?php if(!empty($this->title)): ?>
    <div class="panel-heading border-bottom">
        <span class="f-13"><?= $this->title ?></span>
    </div>
<?php endif; ?>

<!--表单内容-->
<div class="panel-body border-bottom">
    <form>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="addon-fix">邮箱</span>
                </div>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="请输入邮箱">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="addon-fix">密码</span>
                </div>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入密码">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon text-left">
                    <span class="addon-fix text-center">文件上传</span>
                </div>
                <input type="file" class="form-control" id="exampleInputFile">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="addon-fix">文本域</span>
                </div>
                <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="请输入文本"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="addon-fix">单选选择控件</span>
                </div>
                <div class="form-control">
                    <label class="radio-inline">
                        <input type="radio" name="sex" value="男">男
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sex" value="女" checked>女
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="addon-fix">多选选择控件</span>
                </div>
                <div class="form-control">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="inlineCheckbox2" value="option2">中国
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" id="inlineCheckbox3" value="option3">美国
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-default">立即提交</button>
    </form>
</div>
