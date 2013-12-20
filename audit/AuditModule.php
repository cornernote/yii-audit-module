<?php

/**
 * AuditModule
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditModule extends CWebModule
{
    /**
     * @var string the ID of the {@link CDbConnection} application component. If not set,
     * a SQLite3 database will be automatically created and used. The SQLite database file
     * is <code>protected/runtime/audit-AuditVersion.db</code>.
     */
    public $connectionID;

    /**
     * @var boolean whether the DB tables should be created automatically if they do not exist. Defaults to true.
     * If you already have the table created, it is recommended you set this property to be false to improve performance.
     */
    public $autoCreateTables = true;

    /**
     * @var string the ID of the default controller for this module.
     */
    public $defaultController = 'audit';

    /**
     * @var string
     */
    public $layout = 'column1';

    /**
     * @var array
     */
    public $controllerMap = array(
        'error' => 'audit.controllers.AuditErrorController',
        'field' => 'audit.controllers.AuditFieldController',
        'request' => 'audit.controllers.AuditRequestController',
    );

    /**
     * @var array Map of model info including relations and behaviors.
     */
    public $modelMap = array();

    /**
     * Set to false if you do not wish to track database audits.
     * @var bool
     */
    public $enableAuditField = true;

    /**
     * @var array
     */
    public $adminUsers = array();

    /**
     * @var array|string The view url for users, '--user_id--' will be replaced by the actual user_id.
     * <pre>
     * array('/user/view', 'id' => '--user_id--')
     * </pre>
     */
    public $userViewUrl;

    /**
     * @var CDbConnection the DB connection instance
     */
    private $_db;

    /**
     * @var string Url to the assets
     */
    private $_assetsUrl;

    /**
     * @return string
     */
    public static function powered()
    {
        return Yii::t('audit', 'Powered by {yii-audit-module}.', array('{yii-audit-module}' => '<a href="https://github.com/cornernote/yii-audit-module#yii-audit-module" rel="external">Yii Audit Module</a>'));
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Initializes the audit module.
     */
    public function init()
    {
        parent::init();

        // setup paths
        Yii::setPathOfAlias('audit', dirname(__FILE__));
        $this->setImport(array(
            'audit.models.*',
            'audit.components.*',
        ));

        // map models
        foreach ($this->getDefaultModelMap() as $method => $data)
            foreach ($data as $name => $options)
                if (empty($this->modelMap[$method][$name]))
                    $this->modelMap[$method][$name] = $options;

        // setup components
        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'class' => 'audit.components.AuditErrorHandler',
                'errorAction' => $this->getId() . '/audit/error',
            ),
            'widgetFactory' => array(
                'class' => 'system.web.CWidgetFactory',
                'widgets' => array()
            ),
        ), false);
    }


    /**
     * @param CController $controller
     * @param CAction $action
     * @return bool
     */
    public function beforeControllerAction($controller, $action)
    {
        if (!parent::beforeControllerAction($controller, $action))
            return false;
        $route = $controller->id . '/' . $action->id;
        if (!in_array(Yii::app()->user->getName(), $this->adminUsers) && $route !== 'default/error')
            throw new CHttpException(403, "You are not allowed to access this page.");
        return true;
    }

    /**
     * @return array
     */
    public function getDefaultModelMap()
    {
        return array(
            'AuditRequest' => array(
                'relations' => array(
                    'auditErrors' => array(
                        'CHasManyRelation',
                        'AuditError',
                        'audit_request_id',
                    ),
                    'auditFields' => array(
                        'CHasManyRelation',
                        'AuditField',
                        'audit_request_id',
                    ),
                    'auditFieldCount' => array(
                        'CStatRelation',
                        'AuditField',
                        'audit_request_id',
                    ),
                ),
            ),
            'AuditField' => array(
                'relations' => array(
                    'auditRequest' => array(
                        'CBelongsToRelation',
                        'AuditRequest',
                        'audit_request_id',
                    ),
                ),
            ),
            'AuditError' => array(
                'relations' => array(
                    'auditRequest' => array(
                        'CBelongsToRelation',
                        'AuditRequest',
                        'audit_request_id',
                    ),
                ),
            ),
        );
    }

    /**
     * @return CDbConnection the DB connection instance
     * @throws CException if {@link connectionID} does not point to a valid application component.
     */
    public function getDbConnection()
    {
        if ($this->_db !== null)
            return $this->_db;
        elseif (($id = $this->connectionID) !== null) {
            if (($this->_db = Yii::app()->getComponent($id)) instanceof CDbConnection)
                return $this->_db;
            else
                throw new CException(Yii::t('audit', 'AuditModule.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
                    array('{id}' => $id)));
        }
        else {
            $dbFile = Yii::app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'audit-' . $this->getVersion() . '.db';
            return $this->_db = new CDbConnection('sqlite:' . $dbFile);
        }
    }

    /**
     * Sets the DB connection used by the cache component.
     * @param CDbConnection $value the DB connection instance
     * @since 1.1.5
     */
    public function setDbConnection($value)
    {
        $this->_db = $value;
    }

    /**
     * @return string the base URL that contains all published asset files of audit.
     */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('audit.assets'));
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of audit.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl = $value;
    }

    /**
     * @param $user_id
     * @return int|string
     */
    public function userViewLink($user_id)
    {
        if (!$this->userViewUrl)
            return $user_id;
        return str_replace('--user_id--', $user_id, CHtml::link('--user_id--', $this->userViewUrl));
    }

}
