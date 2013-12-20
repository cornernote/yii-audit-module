<?php
/**
 * @var $this AuditRequestController
 * @var $auditRequest AuditRequest
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
$form->searchToggle('auditRequest-grid-search', 'auditRequest-grid');

echo '<fieldset>';
echo '<legend>' . Yii::t('audit', 'Search') . '</legend>';
echo $form->textFieldRow($auditRequest, 'id');
echo $form->textFieldRow($auditRequest, 'link');
echo $form->textFieldRow($auditRequest, 'user_id');
echo $form->textFieldRow($auditRequest, 'ip');
echo $form->textFieldRow($auditRequest, 'post');
echo $form->textFieldRow($auditRequest, 'get');
echo $form->textFieldRow($auditRequest, 'files');
echo $form->textFieldRow($auditRequest, 'session');
echo $form->textFieldRow($auditRequest, 'server');
echo $form->textFieldRow($auditRequest, 'cookie');
echo $form->textFieldRow($auditRequest, 'referrer');
echo $form->textFieldRow($auditRequest, 'redirect');
echo $form->textFieldRow($auditRequest, 'audit_field_count');
echo $form->textFieldRow($auditRequest, 'start_time');
echo $form->textFieldRow($auditRequest, 'end_time');
echo $form->textFieldRow($auditRequest, 'total_time');
echo $form->textFieldRow($auditRequest, 'memory_usage');
echo $form->textFieldRow($auditRequest, 'memory_peak');
echo $form->textFieldRow($auditRequest, 'created');
echo '</fieldset>';

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();