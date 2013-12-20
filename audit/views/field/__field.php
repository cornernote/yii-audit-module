<?php
/**
 * @var $this CController
 * @var $model CActiveRecord
 * @var $model_name string
 * @var $model_id string
 * @var $field string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$criteria = new CDbCriteria();
$criteria->condition = 'model_name=:model_name AND model_id=:model_id AND field=:field';
if (isset($model)) {
    $model_name = get_class($model);
    $model_id = is_array($model->getPrimaryKey()) ? implode('-', $model->getPrimaryKey()) : $model->getPrimaryKey();
}
$criteria->params = array(
    ':model_name' => $model_name,
    ':model_id' => $model_id,
    ':field' => $field,
);
$dataProvider = new CActiveDataProvider('AuditField', array(
    'criteria' => $criteria,
    'sort' => array(
        'defaultOrder' => 'created DESC, id DESC',
    ),
));

$this->widget('zii.widgets.CListView', array(
    'id' => "auditField-list-$model_name-$model_id-$field",
    'dataProvider' => $dataProvider,
    'itemView' => 'audit.views.field.__field_view',
    'itemsTagName' => 'table',
    'itemsCssClass' => 'table table-condensed table-striped',
));