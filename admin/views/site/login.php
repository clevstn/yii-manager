<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

/* @var $param */
?>
<div class="container-fluid">
    <select name="test" id="selectTest">
        <option value="">请选择操作项</option>
        <option value="Vue">Vue</option>
        <option value="Angular">Angular</option>
        <option value="React">React</option>
    </select>
</div>
<?= $param ?>
<script>
    setTimeout(function () {
        $(function () {
            $('#selectTest').select2({

            })
        });
    }, 1000)
</script>
