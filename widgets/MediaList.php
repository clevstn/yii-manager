<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * 媒体对象列表
 * ```php
 * example 1:
 *
 * html_media_list($link, '钉钉商品自助服务', [
 * 'mediaBody' => [
 *      ['支付金额:' => '100元', '单价:' => '50元/天',],
 *      '你是个好人么啦啦啦',
 *      '姓名:' =>  $v['username'] ?: '--',
 *      '昵称:' => $v['nickname'] ?: '--',
 *      '电话:' => $v['mobile'] ?: '--',
 *  ],
 * 'list' => [
 *      ['支付金额:' => '100元', '单价:' => '50元/天',],
 *      '你是个好人么啦啦啦',
 *      '你是:' => '好人',
 *  ],
 * 'options' => [
 *      'media' => ['class' => '11'],
 *      'mediaLeft' => ['class' => '22'],
 *      'img' => ['class' => '33'],
 *      'mediaRight' => ['class' => '44'],
 *      'mediaHeader' => ['class' => '55'],
 *      'mediaBody' => ['class' => '66'],
 *      'list' => ['class' => '77'],
 *  ],
 * ]);
 *
 * ```
 * @author cleverstone
 * @since ym1.0
 */
class MediaList extends Widget
{
    /**
     * @var string 图像外链
     * - http://localhost.com/media/image/000.jpg
     */
    public $imgUrl;

    /**
     * @var string 媒体头部内容
     * - 佳能 EOS 5D4单机身【镜头根据需求搭配另拍】
     */
    public $mediaHeader;

    /**
     * @var array 媒体内容
     * - string|int key 标题标签
     * - value 内容
     */
    public $mediaBody = [];

    /**
     * @var array 底部列表
     * - string|int key 标题标签
     * - value 内容
     */
    public $list = [];

    /**
     * @var array 选项
     *          - media => ...
     *
     *          - mediaLeft => ['class' => .., 'style' => ...]
     *          - img => ['class' => .., 'style' => ...]
     *
     *          - mediaRight => ...
     *          - mediaHeader => ['class' => .., 'style' => ...]
     *          - mediaBody => ...
     *
     *          - list => ...
     */
    public $options = [];

    /**
     * @return string
     */
    public function run()
    {
        // media left
        $mediaLeft = $this->renderMediaLeft();
        // media right
        $mediaRight = $this->renderMediaRight();

        $mediaContent = $mediaLeft . $mediaRight;
        if (!empty($mediaContent)) {
            if (!empty($this->options['media'])) {
                $mediaOptions = $this->options['media'];
                Html::addCssClass($mediaOptions, ['media']);
            } else {
                $mediaOptions = ['class' => ['media']];
            }

            $media = Html::tag('div', $mediaContent, $mediaOptions);
        } else {
            $media = '';
        }

        return $media . $this->renderList();
    }

    /**
     * 渲染媒体对象下方列表
     * @return string
     */
    public function renderList()
    {
        $p = '';
        foreach ((array)$this->list as $label => $text) {
            if (is_int($label)) {
                if (is_array($text)) {
                    $lineInner = '';
                    foreach ($text as $lineLabel => $lineText) {
                        if (is_int($lineLabel)) {
                            $lineInner .= Html::tag('span', $lineText);
                        } else {
                            $lineInner .= Html::label($lineLabel) . Html::tag('span', $lineText);
                        }
                    }

                    $p .= Html::tag('p', $lineInner);
                } else {
                    $p .= Html::tag('p', $text);
                }

            } else {
                $p .= Html::tag('p', Html::label($label) . Html::tag('span', $text));
            }
        }

        if (!empty($p)) {
            if (!empty($this->options['list'])) {
                $listOptions = $this->options['list'];
                Html::addCssClass($listOptions, ['media-list']);
            } else {
                $listOptions = ['class' => ['media-list']];
            }

            $hr = Html::tag('hr', '', ['class' => ['m-0', 'pt-6']]);
            $list = $hr . Html::tag('div', $p, $listOptions);
        } else {
            $list = '';
        }

        return $list;
    }

    public function renderMediaRight()
    {
        // media header
        if (!empty($this->mediaHeader)) {
            if (!empty($this->options['mediaHeader'])) {
                $mediaHeaderOptions = $this->options['mediaHeader'];
                Html::addCssClass($mediaHeaderOptions, ['media-heading', 'text-primary']);
            } else {
                $mediaHeaderOptions = ['class' => ['media-heading', 'text-primary']];
            }

            $h5 = Html::tag('h5', $this->mediaHeader, $mediaHeaderOptions);
        } else {
            $h5 = '';
        }

        // media body
        $p = '';
        foreach ((array)$this->mediaBody as $label => $text) {
            if (is_int($label)) {
                if (is_array($text)) {
                    $lineInner = '';
                    foreach ($text as $lineLabel => $lineText) {
                        if (is_int($lineLabel)) {
                            $lineInner .= Html::tag('span', $lineText);
                        } else {
                            $lineInner .= Html::label($lineLabel) . Html::tag('span', $lineText);
                        }
                    }

                    $p .= Html::tag('p', $lineInner);
                } else {
                    $p .= Html::tag('p', $text);
                }

            } else {
                $label = Html::label($label);
                $span = Html::tag('span', $text);
                $p .= Html::tag('p', $label . $span);
            }
        }

        if (!empty($p)) {
            if (!empty($this->options['mediaBody'])) {
                $mediaBodyOptions = $this->options['mediaBody'];
                Html::addCssClass($mediaBodyOptions, ['media-body-inner']);
            } else {
                $mediaBodyOptions = ['class' => ['media-body-inner']];
            }

            $mediaBody = Html::tag('div', $p, $mediaBodyOptions);
        } else {
            $mediaBody = '';
        }

        $innerContent = $h5 . $mediaBody;
        if (!empty($innerContent)) {
            if (!empty($this->options['mediaRight'])) {
                $mediaRightOptions = $this->options['mediaRight'];
                Html::addCssClass($mediaRightOptions, ['media-body']);
            } else {
                $mediaRightOptions = ['class' => 'media-body'];
            }

            return Html::tag('div', $innerContent, $mediaRightOptions);
        } else {
            return '';
        }
    }

    /**
     * 渲染媒体对象内左
     * @return string
     */
    public function renderMediaLeft()
    {
        // img
        if (!empty($this->options['img'])) {
            $imgOptions = $this->options['img'];
        } else {
            $imgOptions = [];
        }

        // media left
        if (!empty($this->imgUrl)) {
            $imgTag = Html::img($this->imgUrl, $imgOptions);

            if (!empty($this->options['mediaLeft'])) {
                $mediaLeftOptions = $this->options['mediaLeft'];
                Html::addCssClass($mediaLeftOptions, ['media-left']);
            } else {
                $mediaLeftOptions = ['class' => 'media-left'];
            }

            return Html::tag('div', Html::a($imgTag, $this->imgUrl, ['target' => '_blank']), $mediaLeftOptions);
        }

        return '';
    }
}