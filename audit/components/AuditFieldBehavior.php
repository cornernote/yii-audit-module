<?php

/**
 * AuditFieldBehavior automatically tracks changes to model data.
 *
 * @property CActiveRecord $owner
 * @property CActiveRecord $auditModel
 *
 * This class was inspired by LoggableBehavior by MadSkillsTisdale.
 * @author MadSkillsTisdale
 * @link http://www.yiiframework.com/user/597/
 * @copyright Copyright 2010 MadSkillsTisdale
 * @link http://www.yiiframework.com/extension/audittrail/
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditFieldBehavior extends CActiveRecordBehavior
{

    /**
     * Set to false if you just want to use getDbAttribute and other methods in this class.
     * If left unset the value will come from AuditModule::enableAuditField
     * @var bool
     */
    public $enableAuditField;

    /**
     * The name to use when saving the model_name field.  Defaults to the owners class name.
     * @var string
     */
    public $auditModelName;

    /**
     * Any additional models you want to use to write model and model_id audits to.  If this array is not empty then
     * each field modifed will result in an AuditField being created for each additionalAuditModels.
     * @var array
     * - modelName => modelIdField
     */
    public $additionalAuditModels = array();

    /**
     * A list of values that will be treated as if they were null.
     * @var array
     */
    public $ignoreValues = array('0', '0.0', '0.00', '0.000', '0.0000', '0.00000', '0.000000', '0000-00-00', '0000-00-00 00:00:00');

    /**
     * @var array The attributes that are currently in the database
     */
    private $_dbAttributes = array();

    /**
     * A list of fields to be ignored on update and delete
     * @var array
     * - insert: array()
     * - update: array()
     */
    public $ignoreFields = array(
        'insert' => array('modified', 'modified_by', 'deleted', 'deleted_by'),
        'update' => array('created', 'created_by', 'modified'),
    );

    /**
     * The model that will be used to populate model and model_id fields.
     * @var CActiveRecord
     * @see getAuditModel
     */
    private $_auditModel;

    /**
     * Gets an attribute, as it is in the database
     *
     * @param $attribute
     * @param $default
     * @return mixed
     */
    public function getDbAttribute($attribute, $default = null)
    {
        return isset($this->_dbAttributes[$attribute]) ? $this->_dbAttributes[$attribute] : $default;
    }

    /**
     * Check if any fields have changed
     *
     * @param string $field
     * @return bool|string|array
     */
    public function changed($field = null)
    {
        if ($this->owner->isNewRecord)
            return false;
        if ($field)
            return $this->getDbAttribute($field) != $this->owner->getAttribute($field);
        foreach ($this->owner->getAttributes() as $k => $v)
            if ($this->getDbAttribute($k) != $v)
                return true;
        return false;
    }

    /**
     * Actions to be performed after the model is loaded
     */
    public function afterFind($event)
    {
        $this->_dbAttributes = $this->owner->getAttributes();
        parent::afterFind($event);
    }

    /**
     * Find changes to the model and save them as AuditField records
     * Do not call this method directly, it will be called after the model is saved.
     * @param CModelEvent $event
     */
    public function afterSave($event)
    {
        if (!$this->enableAuditField) {
            parent::afterSave($event);
            return;
        }

        $date = time();
        $newAttributes = $this->owner->attributes;
        $oldAttributes = $this->_dbAttributes;
        $auditModels = $this->getAuditModels();
        $auditRequestId = $this->getAuditRequestId();
        $auditFields = array();
        $userId = Yii::app()->user && Yii::app()->user->id ? Yii::app()->user->id : 0;

        // insert
        if ($this->owner->isNewRecord) {
            foreach ($newAttributes as $name => $new) {
                if (in_array($name, $this->ignoreFields['insert'])) continue;

                // prepare the values
                $new = trim($new);
                if (!$new) continue;

                // prepare the logs
                foreach ($auditModels as $auditModel) {
                    if (isset($auditModel['ignoreFields']) && in_array($name, $auditModel['ignoreFields'])) continue;
                    $auditFields[] = array(
                        'old_value' => '',
                        'new_value' => $new,
                        'action' => 'INSERT',
                        'model_name' => $auditModel['model_name'],
                        'model_id' => $auditModel['model_id'],
                        'field' => $auditModel['prefix'] . $name,
                        'created' => $date,
                        'user_id' => $userId,
                        'audit_request_id' => $auditRequestId,
                    );
                }
            }
        }

        // update
        else {
            // compare old and new
            foreach ($newAttributes as $name => $new) {
                if (in_array($name, $this->ignoreFields['update'])) continue;

                // prepare the values
                $old = !empty($oldAttributes) ? trim($oldAttributes[$name]) : '';
                $new = trim($new);
                if (in_array($old, $this->ignoreValues)) $old = '';
                if (in_array($new, $this->ignoreValues)) $new = '';
                if ($new == $old) continue;

                // prepare the logs
                foreach ($auditModels as $auditModel) {
                    if (isset($auditModel['ignoreFields']) && in_array($name, $auditModel['ignoreFields'])) continue;
                    $auditFields[] = array(
                        'old_value' => $old,
                        'new_value' => $new,
                        'action' => 'UPDATE',
                        'model_name' => $auditModel['model_name'],
                        'model_id' => $auditModel['model_id'],
                        'field' => $auditModel['prefix'] . $name,
                        'created' => $date,
                        'user_id' => $userId,
                        'audit_request_id' => $auditRequestId,
                    );
                }
            }
        }

        // insert the audit_field records
        $this->addAuditFields($auditFields);

        // set the dbAttributes to the new values
        $this->_dbAttributes = $this->owner->attributes;

        parent::afterSave($event);
    }

    /**
     * Find changes to the model and save them as AuditField records.
     * Do not call this method directly, it will be called after the model is deleted.
     * @param CModelEvent $event
     */
    public function afterDelete($event)
    {
        if (!$this->enableAuditField) {
            parent::afterDelete($event);
            return;
        }

        $date = time();
        $auditModels = $this->getAuditModels();
        $auditRequestId = $this->getAuditRequestId();
        $userId = Yii::app()->user && Yii::app()->user->id ? Yii::app()->user->id : 0;
        $auditFields = array();

        // prepare the logs
        $pk = $this->getModelPrimaryKeyString($this->auditModel);
        foreach ($auditModels as $auditModel) {
            $prefix = isset($auditModel['prefix']) ? $auditModel['prefix'] . '.' . $pk : '';
            $auditFields[] = array(
                'old_value' => '',
                'new_value' => '',
                'action' => 'DELETE',
                'model_name' => $auditModel['model_name'],
                'model_id' => $auditModel['model_id'],
                'field' => $prefix . '*',
                'created' => $date,
                'user_id' => $userId,
                'audit_request_id' => $auditRequestId,
            );
        }

        // insert the audit_field records
        $this->addAuditFields($auditFields);

        parent::afterDelete($event);
    }

    /**
     * @param $auditFields
     */
    protected function addAuditFields($auditFields)
    {
        if (!$auditFields)
            return;
        /** @var AuditModule $audit */
        $audit = Yii::app()->getModule('audit');
        $audit->getDbConnection()->getCommandBuilder()->createMultipleInsertCommand(AuditField::model()->tableName(), $auditFields)->execute();
    }

    /**
     * @return int
     */
    protected function getAuditRequestId()
    {
        if (Yii::app()->getErrorHandler() instanceof AuditErrorHandler) {
            $auditRequest = Yii::app()->getErrorHandler()->getAuditRequest();
            if ($auditRequest)
                return $auditRequest->id;
        }
        return 0;
    }

    /**
     * Gets the model to be used in the model and model_id fields.
     * If a method exists in the owner called getAuditModel() it must return an CActiveRecord which will be used.
     * Otherwise the owner model itself will be used.
     * @return CActiveRecord
     */
    protected function getAuditModel()
    {
        if ($this->_auditModel)
            return $this->_auditModel;
        if (method_exists($this->owner, 'getAuditModel'))
            return $this->auditModel = call_user_func(array($this->owner, 'getAuditModel'));
        return $this->_auditModel = $this->owner;
    }

    /**
     * Gets additional models to be used in the model and model_id fields.
     * @return array
     * @see additionalAuditModels
     */
    protected function getAuditModels()
    {
        $auditModels = array();

        // get log models
        if ($this->auditModel) {
            $auditModels[] = array(
                'model_name' => $this->auditModelName ? $this->auditModelName : get_class($this->auditModel),
                'model_id' => $this->getModelPrimaryKeyString($this->auditModel),
                'prefix' => $this->getModelFieldPrefix($this->auditModel),
            );
        }

        // also log to additionalAuditModels
        foreach ($this->additionalAuditModels as $model => $fk_field) {
            $auditModels[] = array(
                'model_name' => $model,
                'model_id' => $this->owner->$fk_field,
                'prefix' => get_class($this->owner) . '.',
                'ignoreFields' => array($fk_field),
            );
        }

        return $auditModels;
    }

    /**
     * If the model is not the same as the owner then prefix the field so we know the model.
     * @param $model CActiveRecord
     * @return string
     */
    protected function getModelFieldPrefix($model)
    {
        return (get_class($this->owner) != get_class($model)) ? get_class($this->owner) . '.' : '';
    }

    /**
     * Returns Primary Key as a string
     * @param $model CActiveRecord
     * @return string
     */
    protected function getModelPrimaryKeyString($model)
    {
        return is_array($model->getPrimaryKey()) ? implode('-', $model->getPrimaryKey()) : $model->getPrimaryKey();
    }

    /**
     * @param CComponent $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if ($this->enableAuditField === null) {
            /** @var AuditModule $audit */
            $audit = Yii::app()->getModule('audit');
            $this->enableAuditField = $audit->enableAuditField;
        }
    }

}
