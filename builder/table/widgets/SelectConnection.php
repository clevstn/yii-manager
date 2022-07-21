<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\table\widgets;

use yii\helpers\Html;
use yii\base\BaseObject;
use app\builder\table\CustomControl;

/**
 * 三级联动部件
 * @property-read string $valuesJsFunction 获取用于获取筛选值的Js脚本
 * @author cleverstone
 * @since 1.0
 */
class SelectConnection extends BaseObject implements CustomControl
{
    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return Html::input('text', 'custom', '', ['id' => 'custom_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function clearValuesJsFunction()
    {
        return <<<'JS'
        function custom () {
            jQuery("#custom_id").val("");
            return true;
        }
JS;
    }

    /**
     * {@inheritDoc}
     */
    public function getValuesJsFunction()
    {
        return <<<'JS'
        function custom () {
            return {
                custom: jQuery("#custom_id").val(),
            };
        }
JS;
    }

    /**
     * {@inheritDoc}
     */
    public function initValuesJsFunction()
    {
        return <<<'JS'
        function custom () {
            return jQuery("#custom_id").val("1");
        }
JS;
    }
}