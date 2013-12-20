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

$columns = array();
$columns[] = array(
    'class' => 'zii.widgets.grid.CButtonColumn',
    'template' => '{view}',
    'headerHtmlOptions' => array('style' => 'width:auto;'),
    'htmlOptions' => array('style' => 'width:auto;'),
);
$columns[] = array(
    'name' => 'id',
);
$columns[] = array(
    'name' => 'link',
    'value' => '$data->getLinkString()',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'referrer',
    'value' => '$data->getReferrerString()',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'redirect',
);
$columns[] = array(
    'name' => 'audit_field_count',
);
$columns[] = array(
    'name' => 'total_time',
    'value' => 'number_format($data->total_time,3)',
);
$columns[] = array(
    'name' => 'memory_peak',
    'value' => 'number_format($data->memory_peak/1024/1024,2)',
);
$columns[] = array(
    'name' => 'user_id',
    'value' => 'Yii::app()->getModule("audit")->userViewLink($data->auditRequest->user_id)',
    'type' => 'raw',
);
$columns[] = array(
    'name' => 'ip',
);
$columns[] = array(
    'name' => 'created',
    'value' => 'date("Y-m-d H:i:s", $data->created)',
);

// grid
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'auditRequest-grid',
    'dataProvider' => $auditRequest->search(),
    'filter' => $auditRequest,
    'columns' => $columns,
));