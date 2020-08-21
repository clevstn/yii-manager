<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

/* @var $this \yii\web\View */
/* @var $param */

$this->title = '登录';
$this->params['breadcrumbs'][] = '登录';
?>

<div class="form-group form-group-sm">
    <select name="test" id="selectTest" class="form-control">
        <option value="">请选择操作项</option>
        <option value="Vue">Vue</option>
        <option value="Angular">Angular</option>
        <option value="React">React</option>
    </select>
</div>
<div class="form-group form-group-sm">
    <input type="text" class="form-control">
</div>

<?= $param ?>
<script>
    setTimeout(function () {
        $(function () {
            $('#selectTest').select2({
                width: "100%",
            })
        });
    }, 1000)
</script>
