<?php

namespace app\controllers;

class Admin1Controller extends CommonController
{
    public function actionIndex()
    {
        echo '测试一下basic模块中的`admin`控制器是否和模块`admin`冲突';
    }
}