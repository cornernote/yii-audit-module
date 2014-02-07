<?php
/**
 * @var $this AuditFieldController
 * @var $auditField AuditField
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
$form->searchToggle('auditField-grid-search', 'auditField-grid');

echo $form->textFieldControlGroup($auditField, 'id');
echo $form->textFieldControlGroup($auditField, 'audit_request_id');
echo $form->textFieldControlGroup($auditField, 'user_id');
echo $form->textFieldControlGroup($auditField, 'old_value');
echo $form->textFieldControlGroup($auditField, 'new_value');
echo $form->textFieldControlGroup($auditField, 'action');
echo $form->textFieldControlGroup($auditField, 'model_name');
echo $form->textFieldControlGroup($auditField, 'model_id');
echo $form->textFieldControlGroup($auditField, 'field');
echo $form->textFieldControlGroup($auditField, 'created');

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();