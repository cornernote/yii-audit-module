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

$this->pageTitle = Yii::t('audit', 'Log ID-:id', array(':id' => $auditLog->id));

$attributes = array();
$attributes[] = array(
    'name' => 'id',
);
$attributes[] = array(
    'name' => 'audit_request_id',
    'value' => CHtml::link($auditLog->audit_request_id, array('request/view', 'id' => $auditLog->audit_request_id)),
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'user_id',
    'value' => $this->module->userViewLink($auditLog->user_id),
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'level',
);
$attributes[] = array(
    'name' => 'category',
);
$attributes[] = array(
    'name' => 'message',
    'value' => '<pre><small>' . $auditLog->formatMessage() . '</small></pre>',
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'file',
    'value' => $auditLog->getFileAlias(),
);
$attributes[] = array(
    'name' => 'created',
    'value' => Yii::app()->format->formatDatetime($auditLog->created),
);

$this->widget(Yii::app()->getModule('audit')->detailViewWidget, array(
    'data' => $auditLog,
    'attributes' => $attributes,
    'htmlOptions' => array(
        'class' => 'table table-condensed table-striped',
    ),
));