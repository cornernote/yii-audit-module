<?php

/**
 * AuditError
 *
 * --- BEGIN ModelDoc ---
 *
 * Table audit_error
 * @property integer $id
 * @property integer $audit_request_id
 * @property integer $code
 * @property string $type
 * @property string $error_code
 * @property string $message
 * @property string $file
 * @property string $line
 * @property string $trace
 * @property string $traces
 * @property string $source_code
 * @property string $stack_trace
 * @property string $extra
 * @property string $hash
 * @property string $status
 * @property integer $created
 *
 * Relations
 * @property \AuditRequest $auditRequest
 *
 * @see \CActiveRecord
 * @method \AuditError find($condition = '', array $params = array())
 * @method \AuditError findByPk($pk, $condition = '', array $params = array())
 * @method \AuditError findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditError findBySql($sql, array $params = array())
 * @method \AuditError[] findAll($condition = '', array $params = array())
 * @method \AuditError[] findAllByPk($pk, $condition = '', array $params = array())
 * @method \AuditError[] findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditError[] findAllBySql($sql, array $params = array())
 * @method \AuditError with()
 * @method \AuditError together()
 * @method \AuditError cache($duration, $dependency = null, $queryCount = 1)
 * @method \AuditError resetScope($resetDefault = true)
 * @method \AuditError populateRecord($attributes, $callAfterFind = true)
 * @method \AuditError[] populateRecords($data, $callAfterFind = true, $index = null)
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
class AuditError extends AuditActiveRecord
{

    /**
     * @var int used for search
     */
    public $user_id;

    /**
     * @param string $className
     * @return AuditError
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
            'audit_request_id' => Yii::t('audit', 'Audit Request'),
            'code' => Yii::t('audit', 'Code'),
            'type' => Yii::t('audit', 'Type'),
            'error_code' => Yii::t('audit', 'Error Code'),
            'message' => Yii::t('audit', 'Message'),
            'file' => Yii::t('audit', 'File'),
            'line' => Yii::t('audit', 'Line'),
            'trace' => Yii::t('audit', 'Trace'),
            'traces' => Yii::t('audit', 'Traces'),
            'created' => Yii::t('audit', 'Created'),
            'user_id' => Yii::t('audit', 'User'),
            'hash' => Yii::t('audit', 'Hash'),
            'status' => Yii::t('audit', 'Status'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if ($this->scenario == 'search') {
            $rules[] = array('id, audit_request_id, code, type, error_code, message, file, line, trace, traces, created, user_id, hash, status', 'safe');
        }
        return $rules;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.audit_request_id', $this->audit_request_id);
        $criteria->compare('t.code', $this->code);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.error_code', $this->error_code, true);
        $criteria->compare('t.message', $this->message, true);
        $criteria->compare('t.file', $this->file, true);
        $criteria->compare('t.line', $this->line, true);
        $criteria->compare('t.trace', $this->trace, true);
        $criteria->compare('t.traces', $this->traces, true);
        $criteria->compare('t.created', $this->created);
        $criteria->compare('t.hash', $this->hash);
        $criteria->compare('t.status', $this->status);
        if ($this->user_id) {
            $criteria->with[] = 'auditRequest';
            $criteria->compare('auditRequest.user_id', $this->user_id);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }


    /**
     * @return mixed
     */
    public function getFileAlias()
    {
        $file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $this->file);
        $aliases = array('audit', 'zii', 'system', 'application', 'ext', 'modules');
        foreach ($aliases as $alias) {
            $aliasPath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, Yii::getPathOfAlias($alias));
            if (!$aliasPath)
                continue;
            if (strpos($file, $aliasPath) !== false)
                return str_replace(DIRECTORY_SEPARATOR, '.', str_replace($aliasPath, $alias, $file));
        }
        return $file;
    }
}