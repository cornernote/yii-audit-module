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
    'class' => 'zii.widgets.grid.CButtonColumn',
    'template' => '{view}',
    'headerHtmlOptions' => array('style' => 'width:auto;'),
    'htmlOptions' => array('style' => 'width:auto;'),
);
$columns[] = array(
    'name' => 'id',
);
if ($this->id != 'request') {
    $columns[] = array(
        'name' => 'audit_request_id',
        'value' => 'CHtml::link($data->audit_request_id, array("request/view", "id" => $data->audit_request_id))',
        'type' => 'raw',
    );
}
$columns[] = array(
    'name' => 'user_id',
    'value' => 'Yii::app()->getModule("audit")->userViewLink($data->auditRequest->user_id)',
    'type' => 'raw',
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
    'value' => '$data->fileAlias',
);
$columns[] = array(
    'name' => 'line',
);
$columns[] = array(
    'name' => 'created',
    'value' => 'date("Y-m-d H:i:s", $data->created)',
);

// grid
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'auditError-grid',
    'dataProvider' => $auditError->search(),
    'filter' => $auditError,
    'columns' => $columns,
));