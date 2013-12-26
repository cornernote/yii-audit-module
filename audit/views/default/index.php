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

$this->pageTitle = $this->module->getName();
$this->pageHeading = false;

$content = CHtml::tag('p', array(), Yii::t('audit', 'You may use the following tools to help audit your application.'));
foreach (array_keys($this->module->controllerMap) as $controllerName)
    $content .= ' ' . TbHtml::link(Yii::t('audit', ucfirst($controllerName)), array($controllerName . '/index'), array('class' => 'btn btn-large btn-primary'));
$this->widget('bootstrap.widgets.TbHeroUnit', array(
    'heading' => $this->module->getName(),
    'content' => $content,
));
