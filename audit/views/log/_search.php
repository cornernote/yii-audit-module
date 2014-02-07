<?php
/**
 * @var $this AuditLogController
 * @var $auditLog AuditLog
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

/** @var AuditActiveForm $form */
$form = $this->beginWidget('audit.components.AuditActiveForm', array(
    'method' => 'get',
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('auditLog-grid-search', 'auditLog-grid');

echo $form->textFieldControlGroup($auditLog, 'id');
echo $form->textFieldControlGroup($auditLog, 'audit_request_id');
echo $form->textFieldControlGroup($auditLog, 'user_id');
echo $form->textFieldControlGroup($auditLog, 'level');
echo $form->textFieldControlGroup($auditLog, 'category');
echo $form->textFieldControlGroup($auditLog, 'message');
echo $form->textFieldControlGroup($auditLog, 'created');

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();