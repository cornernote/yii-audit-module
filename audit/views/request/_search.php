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
$form = $this->beginWidget('audit.components.AuditActiveForm', array(
    'method' => 'get',
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions' => array('style' => 'display:none;'),
));
$form->searchToggle('auditRequest-grid-search', 'auditRequest-grid');

echo $form->textFieldControlGroup($auditRequest, 'id');
echo $form->textFieldControlGroup($auditRequest, 'link');
echo $form->textFieldControlGroup($auditRequest, 'user_id');
echo $form->textFieldControlGroup($auditRequest, 'ip');
echo $form->textFieldControlGroup($auditRequest, 'post');
echo $form->textFieldControlGroup($auditRequest, 'get');
echo $form->textFieldControlGroup($auditRequest, 'files');
echo $form->textFieldControlGroup($auditRequest, 'session');
echo $form->textFieldControlGroup($auditRequest, 'server');
echo $form->textFieldControlGroup($auditRequest, 'cookie');
echo $form->textFieldControlGroup($auditRequest, 'referrer');
echo $form->textFieldControlGroup($auditRequest, 'redirect');
echo $form->textFieldControlGroup($auditRequest, 'audit_field_count');
echo $form->textFieldControlGroup($auditRequest, 'start_time');
echo $form->textFieldControlGroup($auditRequest, 'end_time');
echo $form->textFieldControlGroup($auditRequest, 'total_time');
echo $form->textFieldControlGroup($auditRequest, 'memory_usage');
echo $form->textFieldControlGroup($auditRequest, 'memory_peak');
echo $form->textFieldControlGroup($auditRequest, 'created');

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();