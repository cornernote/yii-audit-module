<?php
/**
 * Unit Test Bootstrap
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

// only run once
if (defined('APP_DEFINED'))
    return;
define('APP_DEFINED', true);

// define paths
defined('BASE_PATH') or define('BASE_PATH', realpath(__DIR__ . '/..'));
defined('VENDOR_PATH') or define('VENDOR_PATH', realpath(BASE_PATH . '/../vendor'));
defined('YII_PATH') or define('YII_PATH', realpath(VENDOR_PATH . '/yiisoft/yii/framework'));

// disable Yii error handling logic
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);

// create application
require_once(YII_PATH . '/yii.php');
Yii::createWebApplication(BASE_PATH . '/_config.php');