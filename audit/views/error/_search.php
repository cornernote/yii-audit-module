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
$form = $this->beginWidget('audit.widgets.AuditActiveForm', array(
    'method' => 'get',
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('auditError-grid-search', 'auditError-grid');

echo $form->textFieldRow($auditError, 'id');
echo $form->textFieldRow($auditError, 'audit_request_id');
echo $form->textFieldRow($auditError, 'code');
echo $form->textFieldRow($auditError, 'type');
echo $form->textFieldRow($auditError, 'error_code');
echo $form->textFieldRow($auditError, 'message');
echo $form->textFieldRow($auditError, 'file');
echo $form->textFieldRow($auditError, 'line');
echo $form->textFieldRow($auditError, 'trace');
echo $form->textFieldRow($auditError, 'traces');
echo $form->textFieldRow($auditError, 'created');

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();