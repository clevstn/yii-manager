<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\contract;

/**
 * 附件数据模型接口
 * @author cleverstone
 * @since ym1.0
 */
interface AttachmentModelInterface
{
    /**
     * 存储附件
     * @param array $data 附件数据
     * @return \app\builder\common\CommonActiveRecord
     */
    public function saveData(array $data);
}