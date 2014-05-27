<?php

/**
 * AuditLog
 *
 * --- BEGIN ModelDoc ---
 *
 * Table audit_log
 * @property integer $id
 * @property integer $audit_request_id
 * @property integer $user_id
 * @property string $level
 * @property string $category
 * @property string $message
 * @property string $file
 * @property integer $created
 *
 * @see \CActiveRecord
 * @method \AuditLog find($condition = '', array $params = array())
 * @method \AuditLog findByPk($pk, $condition = '', array $params = array())
 * @method \AuditLog findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditLog findBySql($sql, array $params = array())
 * @method \AuditLog[] findAll($condition = '', array $params = array())
 * @method \AuditLog[] findAllByPk($pk, $condition = '', array $params = array())
 * @method \AuditLog[] findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method \AuditLog[] findAllBySql($sql, array $params = array())
 * @method \AuditLog with()
 * @method \AuditLog together()
 * @method \AuditLog cache($duration, $dependency = null, $queryCount = 1)
 * @method \AuditLog resetScope($resetDefault = true)
 * @method \AuditLog populateRecord($attributes, $callAfterFind = true)
 * @method \AuditLog[] populateRecords($data, $callAfterFind = true, $index = null)
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
class AuditLog extends AuditActiveRecord
{

    /**
     * @var
     */
    private $_textHighlighter;

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
            'id' => Yii::t('app', 'ID'),
            'audit_request_id' => Yii::t('app', 'Audit Request'),
            'user_id' => Yii::t('app', 'User'),
            'level' => Yii::t('app', 'Level'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
            'created' => Yii::t('app', 'Created'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if ($this->scenario == 'search') {
            $rules[] = array('id, audit_request_id, user_id, level, category, message, created', 'safe');
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
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.level', $this->level, true);
        $criteria->compare('t.category', $this->category, true);
        $criteria->compare('t.message', $this->message, true);
        $criteria->compare('t.created', $this->created);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    /**
     * @return string
     */
    public function formatMessage()
    {
        $message = AuditHelper::unpack($this->message);
        if ($this->level == 'profile')
            $message = $this->formatProfileMessage($message);
        else
            $message = $this->formatLogMessage($message);
        return $message;
    }

    /**
     * Format profile message
     *
     * @param string $message
     * @return string
     */
    public function formatProfileMessage($message)
    {
        $sqlStart = strpos($message, '(') + 1;
        $sqlEnd = strrpos($message, ')');
        $sqlLength = $sqlEnd - $sqlStart;
        $message = substr($message, $sqlStart, $sqlLength);
        $message = strtr($message, array(
            ' FROM ' => "\nFROM ",
            ' VALUES ' => "\nVALUES ",
            ' WHERE ' => "\nWHERE ",
            ' ORDER BY ' => "\nORDER BY ",
            ' GROUP BY ' => "\nGROUP BY ",
            ' LIMIT ' => "\nLIMIT ",
            ' AND ' => "\n\tAND ",
            ', ' => ",\n\t ",
        ));
        if (strpos($message, '. Bound with ') !== false) {
            list($query, $params) = explode('. Bound with ', $message);
            $binds = array();
            $matchResult = preg_match_all("/(?<key>[a-z0-9\.\_\-\:]+)=(?<value>[\d\.e\-\+]+|''|'.+?(?<!\\\)')/ims", $params, $paramsMatched, PREG_SET_ORDER);
            if ($matchResult) {
                foreach ($paramsMatched as $paramsMatch)
                    if (isset($paramsMatch['key'], $paramsMatch['value']))
                        $binds[':' . trim($paramsMatch['key'], ': ')] = trim($paramsMatch['value']);
            }
            $message = strtr($query, $binds);
        }
        return strip_tags($this->getTextHighlighter()->highlight($message), '<div>,<span>');
    }

    /**
     * @param $message
     * @return string
     */
    public function formatLogMessage($message)
    {
        //return $message;
        return AuditHelper::replaceFileWithAlias($message);
    }

    /**
     * @return string
     */
    public function getFileAlias()
    {
        return AuditHelper::replaceFileWithAlias(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $this->file));
    }

    /**
     * @return CTextHighlighter the text highlighter
     */
    private function getTextHighlighter()
    {
        if ($this->_textHighlighter)
            return $this->_textHighlighter;
        return $this->_textHighlighter = Yii::createComponent(array(
            'class' => 'CTextHighlighter',
            'language' => 'sql',
            'showLineNumbers' => false,
        ));
    }

}