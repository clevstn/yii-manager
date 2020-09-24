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
                    <span class="w-130">邮箱</span>
                </div>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <span class="w-130">密码</span>
                </div>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">File input</label>
            <input type="file" id="exampleInputFile">
            <p class="help-block">Example block-level help text here.</p>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox"> Check me out
            </label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
