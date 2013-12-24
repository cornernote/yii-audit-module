<?php
/**
 * AuditHelper
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditHelper
{

    /**
     * @param $value mixed
     * @return string
     */
    public static function pack($value)
    {
        return gzcompress(serialize($value));
    }

    /**
     * @param $value string
     * @return mixed
     */
    public static function unpack($value)
    {
        return unserialize(gzuncompress($value));
    }


    /**
     * @param $text string
     * @return string
     */
    public static function replaceFileWithAlias($text)
    {
        $aliases = array('audit', 'zii', 'system', 'application', 'ext', 'modules', 'public');
        foreach ($aliases as $alias) {
            $aliasPath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, Yii::getPathOfAlias($alias));
            if (!$aliasPath)
                continue;
            if (stripos($text, $aliasPath) !== false) {
                $text = str_ireplace($aliasPath, $alias, $text);
            }
        }
        return $text;
    }

}
