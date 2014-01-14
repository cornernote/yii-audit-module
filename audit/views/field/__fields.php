<?php
/**
 * @var $this CController
 * @var $model CActiveRecord
 * @var $model_name string
 * @var $model_id string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$auditField = new AuditField('search');
if (isset($_GET['AuditField'])) {
    $auditField->attributes = $_GET['AuditField'];
}
if (isset($model)) {
    $auditField->model_name = get_class($model);
    $auditField->model_id = is_array($model->getPrimaryKey()) ? implode('-', $model->getPrimaryKey()) : $model->getPrimaryKey();
}
else {
    $auditField->model_name = $model_name;
    $auditField->model_id = $model_id;
}
$this->renderPartial('audit.views.field._grid', array(
    'auditField' => $auditField,
));
