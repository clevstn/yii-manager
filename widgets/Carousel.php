<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * 轮播图组件
 * ```php
 *  \app\widgets\Carousel::widget([
 *      'images' => $imgMaps,
 *  ]);
 * ```
 *
 * @author cleverstone
 * @since ym1.0
 */
class Carousel extends Widget
{
    /**
     * @var array ui class
     */
    public $carouselClass = [];

    /**
     * @var array ui style
     */
    public $carouselStyle = [];

    /**
     * 是否隐藏指示器
     * @var bool
     */
    public $hideIndicators = false;

    /**
     * @var array 控件class
     */
    public $controlsClass = [];

    /**
     * @var array 图册
     */
    public $images = [];

    /**
     * @var string the prefix to the automatically generated widget IDs.
     * @see getId()
     */
    public static $autoIdPrefix = 'YmXCarousel';

    /**
     * @return string
     */
    public function run()
    {
        $imgCounts = count($this->images);

       $indicators = $this->getIndicators($imgCounts);
       $slides = $this->getSlides();
       $controls = $this->getControls();

       $options = [
           'id' => $this->id,
           'class' => ['carousel', 'slide'],
           'style' => ['width' => '150px', 'height' => '150px'],
           'data-ride' => 'carousel',
           'x-data-ride' => 'carousel',
       ];
       Html::addCssClass($options, $this->carouselClass);
       Html::addCssStyle($options, $this->carouselStyle);
       $carousel = Html::tag('div', $indicators . $slides . $controls, $options);

       return $carousel;
    }

    /**
     * 获取指示器
     * @param int $imgCounts 图册数量
     * @return string
     */
    protected function getIndicators($imgCounts)
    {
        if (!$this->hideIndicators) {
            $items = array_fill(0, $imgCounts, '');

            return Html::ol($items, [
                'class' => ['carousel-indicators'],
                'item' => function ($item, $index) {
                    if ($index == 0) {
                        return Html::tag('li', '', [
                            'class' => ['active'],
                            'data-target' => "#{$this->id}",
                            'data-slide-to' => $index,
                        ]);
                    } else {
                        return Html::tag('li', '', [
                            'data-target' => "#{$this->id}",
                            'data-slide-to' => $index,
                        ]);
                    }
                },
            ]);
        }

        return '';
    }

    /**
     * 获取滚动相册
     * @return string
     */
    protected function getSlides()
    {
        $results = [];
        foreach ($this->images as $i => $src) {
            $itemClass = ['item'];
            if ($i == 0) {
                $itemClass[] = 'active';
            }

            $img = Html::a(Html::img($src), $src, ['target' => '_blank']);
            $results[] = Html::tag('div', $img, [
                'class' => $itemClass,
            ]);
        }

        return Html::tag('div', implode("\n", $results), [
            'class' => ['carousel-inner'],
        ]);
    }

    /**
     * 获取控制按钮
     * @return string
     */
    protected function getControls()
    {
        $optionL = [
            'class' => ['glyphicon', 'glyphicon-chevron-left'],
        ];
        Html::addCssClass($optionL, $this->controlsClass);
        $span = Html::tag('span', '', $optionL);
        $prev = Html::tag('a', $span, [
            'class' => ['left', 'carousel-control'],
            'href' => "#{$this->id}",
            'data-slide' => 'prev',
        ]);

        $optionR = [
            'class' => ['glyphicon', 'glyphicon-chevron-right'],
        ];
        Html::addCssClass($optionR, $this->controlsClass);
        $span = Html::tag('span', '', $optionR);
        $next = Html::tag('a', $span, [
            'class' => ['right', 'carousel-control'],
            'href' => "#{$this->id}",
            'data-slide' => 'next',
        ]);

        return $prev . "\n" . $next;
    }
}