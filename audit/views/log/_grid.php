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

$columns = array();
$columns[] = array(
    'name' => 'id',
    'value' => 'CHtml::link($data->id, array("/audit/log/view", "id" => $data->id))',
    'type' => 'raw',
);
if ($this->id != 'request') {
    $columns[] = array(
        'name' => 'audit_request_id',
        'value' => 'CHtml::link($data->audit_request_id, array("/audit/request/view", "id" => $data->audit_request_id))',
        'type' => 'raw',
    );
    $columns[] = array(
        'name' => 'user_id',
        'value' => 'Yii::app()->getModule("audit")->userViewLink($data->user_id)',
        'type' => 'raw',
    );
}
$columns[] = array(
    'name' => 'level',
);
$columns[] = array(
    'name' => 'category',
);
$columns[] = array(
    'name' => 'message',
    'value' => '$data->formatMessage()',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'file',
    'value' => '$data->getFileAlias()',
);
if ($this->id != 'request') {
    $columns[] = array(
        'name' => 'created',
        'value' => 'Yii::app()->format->formatDatetime($data->created)',
    );
}

// grid
$this->widget(Yii::app()->getModule('audit')->gridViewWidget, array(
    'id' => 'auditLog-grid',
    'dataProvider' => $auditLog->search(),
    'filter' => $auditLog,
    'columns' => $columns,
));