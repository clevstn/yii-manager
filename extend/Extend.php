<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\extend;

use yii\base\BaseObject;
use app\extend\qrcode\Qrcode;
use yii\base\NotSupportedException;
use app\extend\spreadsheet\Spreadsheet;
use app\extend\google\GoogleAuthenticator;

/**
 * 扩展包装类
 * @method Qrcode qrcode(array $config = []) static Qrcode扩展实例
 * @method GoogleAuthenticator googleAuth(array $config = []) static GoogleAuthenticator扩展实例
 * @method Spreadsheet spreadsheet(array $config = []) static Spreadsheet扩展实例
 * @author HiLi
 * @since 1.0
 */
class Extend extends BaseObject
{
    /**
     * @var array
     */
    protected static $extendPluginsMap = [
        'qrcode' => Qrcode::class,
        'googleAuth' => GoogleAuthenticator::class,
        'spreadsheet' => Spreadsheet::class,
    ];

    /**
     * @param string $name
     * @param array $arguments
     * @return Qrcode|GoogleAuthenticator|Spreadsheet
     * @throws NotSupportedException
     */
    public static function __callStatic($name, $arguments)
    {
        $extendMap = self::$extendPluginsMap;
        if (!empty($extendMap[$name])) {
            $config = [];
            if (!empty($arguments) && !empty(current($arguments))) {
                $config = current($arguments);
            }

            $context = new $extendMap[$name]($config);
            return $context;
        }

        throw new NotSupportedException($name . ' method is not supported. ');
    }
}