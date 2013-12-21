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
$form = $this->beginWidget('audit.widgets.AuditActiveForm', array(
    'method' => 'get',
    'htmlOptions' => array('class' => 'hide'),
));
$form->searchToggle('auditLog-grid-search', 'auditLog-grid');

echo '<fieldset>';
echo '<legend>' . Yii::t('audit', 'Search') . '</legend>';
echo $form->textFieldRow($auditLog, 'id');
echo $form->textFieldRow($auditLog, 'audit_request_id');
echo $form->textFieldRow($auditLog, 'user_id');
echo $form->textFieldRow($auditLog, 'level');
echo $form->textFieldRow($auditLog, 'category');
echo $form->textFieldRow($auditLog, 'message');
echo $form->textFieldRow($auditLog, 'created');
echo '</fieldset>';

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();