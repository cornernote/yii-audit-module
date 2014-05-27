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

$errorHandler = Yii::app()->getErrorHandler();
$cs = Yii::app()->clientScript;
$cs->registerCssFile($this->module->getAssetsUrl() . '/css/error.css');
$cs->registerScriptFile($this->module->getAssetsUrl() . '/js/error.js');

$this->pageTitle = Yii::t('audit', 'Error ID-:id', array(':id' => $auditError->id));

$details = CHtml::tag('small', array(), Yii::t('audit', ':type on :date by :user with :auditRequest:', array(
    ':date' => Yii::app()->format->formatDatetime($auditError->created),
    ':type' => $auditError->type,
    ':user' => $this->module->userViewLink($auditError->auditRequest->user_id, 'User ID-'),
    ':auditRequest' => CHtml::link(Yii::t('audit', 'Request ID-') . $auditError->audit_request_id, array('request/view', 'id' => $auditError->audit_request_id)),
)));

echo CHtml::tag('p', array('class' => 'message'), $details . Yii::app()->format->formatNtext("\n" . $auditError->message));

echo CHtml::tag('div', array(
    'class' => 'source',
), CHtml::tag('p', array('class' => 'file'), htmlspecialchars($auditError->file, ENT_QUOTES, Yii::app()->charset) . '(' . $auditError->line . ')') . AuditHelper::unpack($auditError->source_code));

if ($auditError->stack_trace) {
    echo CHtml::tag('div', array(
        'class' => 'traces',
    ), CHtml::tag('h2', array(), Yii::t('audit', 'Stack Trace')) . AuditHelper::unpack($auditError->stack_trace));
}

if ($auditError->extra) {
    echo CHtml::tag('div', array(
        'class' => 'extra',
    ), CHtml::tag('h2', array(), Yii::t('audit', 'Extra')) . AuditHelper::unpack($auditError->extra));
}