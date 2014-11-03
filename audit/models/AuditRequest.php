<?php

/**
 * AuditRequest
 *
 * --- BEGIN ModelDoc ---
 *
 * Table audit_request
 * @property integer $id
 * @property string $app
 * @property string $link
 * @property integer $user_id
 * @property string $ip
 * @property string $post
 * @property string $get
 * @property string $files
 * @property string $session
 * @property string $server
 * @property string $cookie
 * @property string $config
 * @property string $referrer
 * @property string $redirect
 * @property integer $audit_field_count
 * @property number $start_time
 * @property number $end_time
 * @property number $total_time
 * @property integer $memory_usage
 * @property integer $memory_peak
 * @property integer $created
 *
 * Relations
 * @property \AuditError[] $auditErrors
 * @property \AuditField[] $auditFields
 * @property integer $auditFieldCount
 *
 * @see \CActiveRecord
 * @method \AuditRequest find($condition = '', array $params = array())
 * @method \AuditRequest findByPk($pk, $condition = '', array $params = array())
 * @method \AuditRequest findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditRequest findBySql($sql, array $params = array())
 * @method \AuditRequest[] findAll($condition = '', array $params = array())
 * @method \AuditRequest[] findAllByPk($pk, $condition = '', array $params = array())
 * @method \AuditRequest[] findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditRequest[] findAllBySql($sql, array $params = array())
 * @method \AuditRequest with()
 * @method \AuditRequest together()
 * @method \AuditRequest cache($duration, $dependency = null, $queryCount = 1)
 * @method \AuditRequest resetScope($resetDefault = true)
 * @method \AuditRequest populateRecord($attributes, $callAfterFind = true)
 * @method \AuditRequest[] populateRecords($data, $callAfterFind = true, $index = null)
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
class AuditRequest extends AuditActiveRecord
{

    /**
     * @var string used in search
     */
    public $model_name;

    /**
     * @var string|int used in search
     */
    public $model_id;

    /**
     * @param string $className
     * @return AuditRequest
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
            'link' => Yii::t('audit', 'Link'),
            'user_id' => Yii::t('audit', 'User'),
            'ip' => Yii::t('audit', 'Ip'),
            'post' => Yii::t('audit', 'Post'),
            'get' => Yii::t('audit', 'Get'),
            'files' => Yii::t('audit', 'Files'),
            'session' => Yii::t('audit', 'Session'),
            'server' => Yii::t('audit', 'Server'),
            'cookie' => Yii::t('audit', 'Cookie'),
            'config' => Yii::t('audit', 'Config'),
            'referrer' => Yii::t('audit', 'Referrer'),
            'redirect' => Yii::t('audit', 'Redirect'),
            'audit_field_count' => Yii::t('audit', 'Audit Field Count'),
            'start_time' => Yii::t('audit', 'Start Time'),
            'end_time' => Yii::t('audit', 'End Time'),
            'total_time' => Yii::t('audit', 'Total Time'),
            'memory_usage' => Yii::t('audit', 'Memory Usage'),
            'memory_peak' => Yii::t('audit', 'Memory Peak'),
            'created' => Yii::t('audit', 'Created'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id, user_id, link, ip, created, audit_field_count, referrer, redirect, total_time, memory_usage, memory_peak, model_name, model_id', 'safe', 'on' => 'search'),
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
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.created', $this->created);
        $criteria->compare('t.ip', $this->ip);
        $criteria->compare('t.link', $this->link, true);
        $criteria->compare('t.audit_field_count', $this->audit_field_count);
        $criteria->compare('t.total_time', $this->total_time);
        $criteria->compare('t.memory_usage', $this->memory_usage);
        $criteria->compare('t.memory_peak', $this->memory_peak);

        if ($this->model_name) {
            $criteria->distinct = true;
            $criteria->compare('t.audit_field_count', '>0');
            $criteria->join .= ' INNER JOIN audit_field ON audit_field.audit_request_id=t.id ';
            $criteria->compare('audit_field.model_name', $this->model_name);
            if ($this->model_id) {
                $criteria->compare('audit_field.model_id', $this->model_id);
            }
        }

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    /**
     * @param null $link
     * @return string
     */
    public function getLinkString($link = null)
    {
        if ($link === null)
            $link = $this->link;
        $path = Yii::app()->getRequest()->getHostInfo() . Yii::app()->request->baseUrl;
        if (strpos($link, $path) === 0) {
            $link = substr($link, strlen($path));
        }
        if (strlen($link) < 64)
            return $link;
        return substr($link, 0, 64) . '...';
    }

    /**
     * @return string
     */
    public function getReferrerString()
    {
        return $this->referrer ? $this->getLinkString($this->referrer) : '';
    }

    /**
     * @return string
     */
    public function getRedirectString()
    {
        return $this->redirect ? $this->getLinkString($this->redirect) : '';
    }

}
