<?php
/**
 * Global Test Config
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

return array(
    'basePath' => BASE_PATH,
    'runtimePath' => realpath(BASE_PATH . '/_runtime'),
    'import' => array(
        'audit.components.*',
        'audit.models.*',
    ),
    'preload' => array('log', 'errorHandler'),
    'aliases' => array(
        'audit' => realpath(BASE_PATH . '/../audit'),
        'bootstrap' => realpath(BASE_PATH . '/../vendor/crisu83/yiistrap'),
    ),
    'controllerMap' => array(
        'site' => 'application._components.SiteController',
    ),
    'components' => array(
        'assetManager' => array(
            'basePath' => realpath(BASE_PATH . '/_www/assets'),
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . realpath(BASE_PATH . '/_runtime') . '/test.db',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'errorHandler' => array(
            'class' => 'audit.components.AuditErrorHandler',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'audit.components.AuditLogRoute',
                    'levels' => 'audit',
                ),
            ),
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
        ),
    ),
    'modules' => array(
        'audit' => array(
            'class' => 'audit.AuditModule',
            'connectionID' => 'db',
            'controllerFilters' => array(),
        ),
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'generatorPaths' => array(
                'vendor.cornernote.gii-modeldoc-generator',
                'bootstrap.gii',
            ),
            'ipFilters' => array('127.0.0.1'),
            'password' => false,
        ),
    ),
);
