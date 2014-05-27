<?php

/**
 * AuditField
 *
 * --- BEGIN ModelDoc ---
 *
 * Table audit_field
 * @property integer $id
 * @property integer $audit_request_id
 * @property integer $user_id
 * @property string $old_value
 * @property string $new_value
 * @property string $action
 * @property string $model_name
 * @property string $model_id
 * @property string $field
 * @property integer $created
 *
 * Relations
 * @property \AuditRequest $auditRequest
 *
 * @see \CActiveRecord
 * @method \AuditField find($condition = '', array $params = array())
 * @method \AuditField findByPk($pk, $condition = '', array $params = array())
 * @method \AuditField findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditField findBySql($sql, array $params = array())
 * @method \AuditField[] findAll($condition = '', array $params = array())
 * @method \AuditField[] findAllByPk($pk, $condition = '', array $params = array())
 * @method \AuditField[] findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditField[] findAllBySql($sql, array $params = array())
 * @method \AuditField with()
 * @method \AuditField together()
 * @method \AuditField cache($duration, $dependency = null, $queryCount = 1)
 * @method \AuditField resetScope($resetDefault = true)
 * @method \AuditField populateRecord($attributes, $callAfterFind = true)
 * @method \AuditField[] populateRecords($data, $callAfterFind = true, $index = null)
 *
 * --- END ModelDoc ---
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditField extends AuditActiveRecord
{

    /**
     * @param string $className
     * @return AuditField
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('audit', 'ID'),
            'old_value' => Yii::t('audit', 'Old Value'),
            'new_value' => Yii::t('audit', 'New Value'),
            'action' => Yii::t('audit', 'Action'),
            'model_name' => Yii::t('audit', 'Model Name'),
            'model_id' => Yii::t('email', 'Model ID'),
            'field' => Yii::t('audit', 'Field'),
            'created' => Yii::t('audit', 'Created'),
            'user_id' => Yii::t('audit', 'User'),
            'audit_request_id' => Yii::t('audit', 'Audit Request'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            // search fields
            array('id, new_value, old_value, action, model_name, field, created, user_id, model_id, audit_request_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.old_value', $this->old_value);
        $criteria->compare('t.new_value', $this->new_value);
        $criteria->compare('t.action', $this->action, true);
        $criteria->compare('t.field', $this->field);
        $criteria->compare('t.created', $this->created);
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.model_id', $this->model_id);
        $criteria->compare('t.audit_request_id', $this->audit_request_id);

        if (is_array($this->model_name))
            $criteria->addInCondition('t.model_name', $this->model_name);
        else
            $criteria->compare('t.model_name', $this->model_name);

        $criteria->mergeWith($this->getDbCriteria());

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

}