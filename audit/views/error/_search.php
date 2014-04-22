<?php
/**
 * @var $this AuditErrorController
 * @var $auditError AuditError
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
$form->searchToggle('auditError-grid-search', 'auditError-grid');

echo $form->textFieldControlGroup($auditError, 'id');
echo $form->textFieldControlGroup($auditError, 'audit_request_id');
echo $form->textFieldControlGroup($auditError, 'hash');
echo $form->textFieldControlGroup($auditError, 'code');
echo $form->textFieldControlGroup($auditError, 'type');
echo $form->textFieldControlGroup($auditError, 'error_code');
echo $form->textFieldControlGroup($auditError, 'message');
echo $form->textFieldControlGroup($auditError, 'file');
echo $form->textFieldControlGroup($auditError, 'line');
echo $form->textFieldControlGroup($auditError, 'trace');
echo $form->textFieldControlGroup($auditError, 'traces');
echo $form->textFieldControlGroup($auditError, 'created');

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();