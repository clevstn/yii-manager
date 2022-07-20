<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\extend\qrcode;

use yii\base\BaseObject;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as EndroidQrcode;

/**
 * Qrcode扩展
 * ```php
 *
 * // return `qrcode` string
 * return Extend::qrcode()->returnStr($content);
 * ```
 *
 * ```php
 *
 * // write `qrcode` to file.
 * return Extend::qrcode()->writeFile($content, $filepath);
 * ```
 *
 * ```php
 *
 * // output `qrcode` image.
 * return Extend::qrcode()->output($content);
 * ```
 *
 * @author HiLi
 * @since 1.0
 */
class Qrcode extends BaseObject
{
    /**
     * @var \Endroid\QrCode\string 图片类型
     */
    public $name = 'png';

    /**
     * @var \Endroid\QrCode\int 图片尺寸
     */
    public $size = 300;

    /**
     * @var \Endroid\QrCode\int 图片外边距
     */
    public $margin = 10;

    /**
     * @var \Endroid\QrCode\string 编码
     */
    public $encoding = 'UTF-8';

    /**
     * @var array 前景色
     */
    public $foregroundColor = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    /**
     * @var array 背景色
     */
    public $backgroundColor = ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0];

    /**
     * @var \Endroid\QrCode\string
     * 如果$roundBlockSize=true时，该配置项有效
     * 为调整清晰适当缩放模式：
     * - EndroidQrcode::ROUND_BLOCK_SIZE_MODE_MARGIN (default) 改变margin
     * - EndroidQrcode::ROUND_BLOCK_SIZE_MODE_ENLARGE 整体放大
     * - EndroidQrcode::ROUND_BLOCK_SIZE_MODE_SHRINK 整体缩小
     */
    public $shrink = EndroidQrcode::ROUND_BLOCK_SIZE_MODE_MARGIN;

    /**
     * @var \Endroid\QrCode\bool
     */
    public $roundBlockSize = true;

    /**
     * @var ErrorCorrectionLevel 纠错等级默认：HIGH
     */
    public $errorCorrectionLevel;

    /**
     * @var \Endroid\QrCode\string 标签配置项
     */
    public $label = '';
    /* @var \Endroid\QrCode\int */
    public $labelFontsize = 16;
    /* @var \Endroid\QrCode\string */
    public $labelFontpath = EndroidQrcode::LABEL_FONT_PATH_DEFAULT;
    /* @var \Endroid\QrCode\string|void */
    public $labelAlignment = null;
    /* @var array|void */
    public $labelMargin = ['t' => 0, 'r' => 10, 'b' => 10, 'l' => 10];

    /**
     * @var \Endroid\QrCode\string LOGO配置项
     */
    public $logo = '';
    public $logoSize = ['w' => 60, 'h' => 60];

    /**
     * @var \Endroid\QrCode\bool 是否使用内置阅读器校验二维码内容
     */
    public $validateResult = false;

    /**
     * @var array 额外配置项
     */
    public $extraOptions = [
        'exclude_xml_declaration' => true, // 当name为svg时，该配置有效
    ];

    /**
     * @var EndroidQrcode
     */
    private $_instance;

    /**
     * 创建Qr实例
     * @param \Endroid\QrCode\string $content
     * @return EndroidQrcode
     */
    private function _instance($content)
    {
        // Create a basic QR code
        $qrCode = new EndroidQrcode($content);

        $qrCode->setSize($this->size);
        $qrCode->setMargin($this->margin);

        // Set advanced options
        $qrCode->setWriterByName($this->name);
        $qrCode->setEncoding($this->encoding);

        /* 纠错等级，默认：高 */
        $errorCorrectionLevel = ErrorCorrectionLevel::HIGH();
        if (!empty($this->errorCorrectionLevel) && $this->errorCorrectionLevel instanceof ErrorCorrectionLevel) {
            $errorCorrectionLevel = $this->errorCorrectionLevel;
        }

        $qrCode->setErrorCorrectionLevel($errorCorrectionLevel);
        $qrCode->setForegroundColor($this->foregroundColor);
        $qrCode->setBackgroundColor($this->backgroundColor);
        $qrCode->setRoundBlockSize($this->roundBlockSize, $this->shrink);

        if (!empty($this->label)) {
            $labelAlignment = LabelAlignment::CENTER();
            if (!empty($this->labelAlignment) && $this->labelAlignment instanceof LabelAlignment) {
                $labelAlignment = $this->labelAlignment;
            }

            $qrCode->setLabel($this->label, $this->labelFontsize, $this->labelFontpath, $labelAlignment, $this->labelMargin);
        }

        if (!empty($this->logo)) {
            $qrCode->setLogoPath($this->logo);
            $qrCode->setLogoSize($this->logoSize['w'], $this->logoSize['h']);
        }

        $qrCode->setValidateResult($this->validateResult);
        // Set additional writer options
        $qrCode->setWriterOptions($this->extraOptions);

        $this->_instance = $qrCode;
        return $qrCode;
    }

    /**
     * 二维码保存为文件
     * @param \Endroid\QrCode\string|string $content
     * @param \Endroid\QrCode\string|string $filepath
     * @return true
     */
    public function writeFile($content, $filepath)
    {
        $this->_instance($content);
        // Save it to a file
        $this->_instance->writeFile($filepath);

        return true;
    }

    /**
     * 返回二维码BASE64字符串
     * @param \Endroid\QrCode\string|string $content
     * @return string
     */
    public function returnStr($content)
    {
        $this->_instance($content);

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $this->_instance->writeDataUri();
        return $dataUri;
    }

    /**
     * 输出二维码
     * @param \Endroid\QrCode\string|string $content
     * @return void
     */
    public function output($content)
    {
        $this->_instance($content);

        // Directly output the QR code
        header('Content-Type: ' . $this->_instance->getContentType());
        echo $this->_instance->writeString();

        exit(0);
    }
}