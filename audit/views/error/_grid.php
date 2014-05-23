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

$columns = array();
$columns[] = array(
    'name' => 'id',
    'value' => 'CHtml::link($data->id, array("/audit/error/view", "id" => $data->id))',
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
        'value' => 'Yii::app()->getModule("audit")->userViewLink($data->auditRequest->user_id)',
        'type' => 'raw',
    );
}
$columns[] = array(
    'name' => 'hash',
);
$columns[] = array(
    'name' => 'code',
);
$columns[] = array(
    'name' => 'type',
);
$columns[] = array(
    'name' => 'error_code',
);
$columns[] = array(
    'name' => 'message',
);
$columns[] = array(
    'name' => 'file',
    'value' => '$data->getFileAlias()',
);
$columns[] = array(
    'name' => 'line',
);
if ($this->id != 'request') {
    $columns[] = array(
        'name' => 'created',
        'value' => 'Yii::app()->format->formatDatetime($data->created)',
    );
}

// grid
$this->widget(Yii::app()->getModule('audit')->gridViewWidget, array(
    'id' => 'auditError-grid',
    'dataProvider' => $auditError->search(),
    'filter' => $auditError,
    'columns' => $columns,
));