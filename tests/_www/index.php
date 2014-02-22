<?php
/**
 * Test Web Entry Script
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

// define paths
define('BASE_PATH', realpath(__DIR__ . '/..'));
define('VENDOR_PATH', realpath(BASE_PATH . '/../vendor'));
define('YII_PATH', realpath(VENDOR_PATH . '/yiisoft/yii/framework'));

// debug
define('YII_DEBUG', true);

// composer autoloader
require_once(VENDOR_PATH . '/autoload.php');

// create application
require_once(YII_PATH . '/yii.php');
Yii::createWebApplication(BASE_PATH . '/_config.php')->run();
