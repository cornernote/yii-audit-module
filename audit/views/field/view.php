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

$this->pageTitle = Yii::t('audit', 'Field ID-:id', array(':id' => $auditField->id));

$attributes = array();
$attributes[] = array(
    'name' => 'id',
);
$attributes[] = array(
    'name' => 'audit_request_id',
    'value' => CHtml::link($auditField->audit_request_id, array('request/view', 'id' => $auditField->audit_request_id)),
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'user_id',
    'value' => $this->module->userViewLink($auditField->user_id),
    'type' => 'raw',
);
$attributes[] = array(
    'name' => 'old_value',
);
$attributes[] = array(
    'name' => 'new_value',
);
$attributes[] = array(
    'name' => 'action',
);
$attributes[] = array(
    'name' => 'model_name',
);
$attributes[] = array(
    'name' => 'model_id',
);
$attributes[] = array(
    'name' => 'field',
);
$attributes[] = array(
    'name' => 'created',
    'value' => Yii::app()->format->formatDatetime($auditField->created),
);

$this->widget(Yii::app()->getModule('audit')->detailViewWidget, array(
    'data' => $auditField,
    'attributes' => $attributes,
    'htmlOptions' => array(
        'class' => 'table table-condensed table-striped',
    ),
));