<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/22
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\database\config;

use app\builder\common\Group;

/**
 * 上传配置
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class UploadGroup extends Group
{
    /**
     * {@inheritDoc}
     */
    public $name = '上传配置';

    /**
     * {@inheritDoc}
     */
    public $code = 'UPLOAD_GROUP';

    /**
     * {@inheritDoc}
     */
    public $desc = '上传配置分组';

    /**
     * {@inheritDoc}
     */
    public $formTips = '上传配置分组';

    /**
     * {@inheritDoc}
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function define()
    {
        return [
            $this->normalizeItem('UPLOAD_FILE_LIMIT', '0', '上传文件大小', '(非图片)文件上传大小限制单位kb, 0:不限制', '(非图片)文件上传大小限制'),
            $this->normalizeItem('UPLOAD_FILE_EXT', 'md,zip,pdf,xls,xlsx', '允许上传的(非图片)文件扩展名', '允许上传的(非图片)文件扩展名', '允许上传的(非图片)文件扩展名'),
            $this->normalizeItem('UPLOAD_IMAGE_LIMIT', '5120', '上传图片大小', '图片上传大小限制单位kb, 0:不限制', '图片上传大小限制单位kb, 0:不限制'),
            $this->normalizeItem('UPLOAD_IMAGE_EXT', 'png,jpg,jpeg', '允许上传的图片扩展名', '允许上传的图片扩展名', '允许上传的图片扩展名'),
            $this->normalizeItem('UPLOAD_IMAGE_WMK', '0', '图片是否添加水印', '是否添加水印, 0:否 1:是', '是否添加水印'),
            $this->normalizeItem('UPLOAD_WMK_IMAGE', '0', '水印图片', '水印图片附件ID', '只有开启水印功能才生效'),
            $this->normalizeItem('UPLOAD_WMK_POSITION', 'lt', '水印位置', '水印位置, lt:左上 tm:上中 rt:右上 rm:右中 rb:右下 bm:下中 lb:左下 lm:左中 mm:居中', '只有开启水印功能才生效'),
            $this->normalizeItem('UPLOAD_WMK_OPACITY', '0.2', '透明度', '透明度值: 0.1-1', '透明度值:0.1-0.5'),
            $this->normalizeItem('UPLOAD_DRIVER', 'local', '上传驱动', '上传驱动方式, 本地:local 七牛:qiniu 亚马逊s3:s3 阿里对象存储:aliOSS', ''),
        ];
    }
}