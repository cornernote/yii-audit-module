<?php
/**
 * @var $this AuditController
 * @var $message string
 * @var $code string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$this->pageTitle = Yii::t('audit', 'Error :code', array(':code' => $code));
echo CHtml::tag('div', array('class' => 'error'), CHtml::encode($message));
