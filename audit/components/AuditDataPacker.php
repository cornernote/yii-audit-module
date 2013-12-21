<?php
/**
 * AuditDataPacker
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditDataPacker
{

    /**
     * @param $value mixed
     * @return string
     */
    public static function pack($value)
    {
        if (@self::unpack($value)) return; //already packed
        return base64_encode(gzcompress(serialize($value)));
    }

    /**
     * @param $value string
     * @return mixed
     */
    public static function unpack($value)
    {
        return @unserialize(gzuncompress(base64_decode($value)));
    }

}
