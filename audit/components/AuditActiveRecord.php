<?php
/**
 * AuditActiveRecord
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditActiveRecord extends CActiveRecord
{
    /**
     * @var CDbConnection the default database connection for all active record classes.
     * By default, this is the 'db' application component.
     * @see getDbConnection
     */
    public static $db;

    /**
     * @var array
     */
    private static $_md = array();

    /**
     * @param string $scenario
     */
    public function __construct($scenario = 'insert')
    {
        $audit = Yii::app()->getModule('audit');
        if ($audit->autoCreateTables) {
            try {
                $this->dbConnection->createCommand("SELECT * FROM {$this->tableName()} LIMIT 1")->execute();
            } catch (Exception $e) {
                $this->createTable();
            }
        }
        parent::__construct($scenario);
    }

    /**
     * Returns the meta-data for this AR
     * @return CActiveRecordMetaData the meta for this AR class.
     */
    public function getMetaData()
    {
        $metaData = parent::getMetaData();
        if ($metaData)
            return $metaData;
        $className = get_class($this);
        if (empty(self::$_md[$className])) { // override this from the parent to force it to get the new MetaData
            self::$_md[$className] = null;
            self::$_md[$className] = new CActiveRecordMetaData($this);
        }
        return self::$_md[$className];
    }

    /**
     * Creates the DB table.
     * @throws CException
     */
    public function createTable()
    {
        $db = $this->getDbConnection();
        $file = Yii::getPathOfAlias('audit.migrations') . '/' . $this->tableName() . '.' . $db->getDriverName();
        $pdo = $this->getDbConnection()->pdoInstance;
        $sql = file_get_contents($file);
        $sql = rtrim($sql);
        $sqls = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sql);
        $sqls = explode(";", $sqls);
        foreach ($sqls as $sql) {
            if (!empty($sql)) {
                $sql = str_replace(" $$$ ", ";", $sql) . ";";
                $pdo->exec($sql);
            }
        }
    }

    /**
     * Guess the table name based on the class
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        $audit = Yii::app()->getModule('audit');
        if (!empty($audit->modelMap[get_class($this)]['tableName']))
            return $audit->modelMap[get_class($this)]['tableName'];
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', get_class($this)));
    }

    /**
     * Returns the relations used for the model
     *
     * @return array
     * @see AuditModule::modelMap
     */
    public function relations()
    {
        $audit = Yii::app()->getModule('audit');
        if (!empty($audit->modelMap[get_class($this)]['relations']))
            return $audit->modelMap[get_class($this)]['relations'];
        return parent::relations();
    }

    /**
     * Returns the behaviors used for the model
     *
     * @return array
     * @see AuditModule::modelMap
     */
    public function behaviors()
    {
        $audit = Yii::app()->getModule('audit');
        if (!empty($audit->modelMap[get_class($this)]['behaviors']))
            return $audit->modelMap[get_class($this)]['behaviors'];
        return parent::behaviors();
    }

    /**
     * @throws CDbException
     * @return mixed
     */
    public function getDbConnection()
    {
        if (self::$db !== null)
            return self::$db;

        /** @var AuditModule $audit */
        $audit = Yii::app()->getModule('audit');
        self::$db = $audit->getDbConnection();
        self::$db->setActive(true);
        return self::$db;
    }

}
