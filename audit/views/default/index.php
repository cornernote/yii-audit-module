<?php
/**
 * @var $this DefaultController
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$this->pageTitle = Yii::t('audit', 'Welcome to the Yii Audit Module!');

$items = array();
foreach (array_keys($this->module->controllerMap) as $controllerName)
    $items[] = array('label' => Yii::t('email', ucfirst($controllerName)), 'url' => array($controllerName . '/index'));
$this->widget('zii.widgets.CMenu', array('items' => $items));
