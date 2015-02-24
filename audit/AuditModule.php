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
     * @var string The ID of the CDbConnection application component. If not set, a SQLite3
     * database will be automatically created in <code>protected/runtime/audit-AuditVersion.db</code>.
     */
    public $connectionID;

    /**
     * @var boolean Whether the DB tables should be created automatically if they do not exist. Defaults to true.
     * If you already have the table created, it is recommended you set this property to be false to improve performance.
     */
    public $autoCreateTables = true;

    /**
     * @var string The layout used for module controllers.
     */
    public $layout = 'audit.views.layouts.column1';

    /**
     * @var string The widget used to render grid views.
     */
    public $gridViewWidget = 'bootstrap.widgets.TbGridView';

    /**
     * @var string The widget used to render detail views.
     */
    public $detailViewWidget = 'zii.widgets.CDetailView';

    /**
     * @var array mapping from controller ID to controller configurations.
     */
    public $controllerMap = array(
        'error' => 'audit.controllers.AuditErrorController',
        'field' => 'audit.controllers.AuditFieldController',
        'log' => 'audit.controllers.AuditLogController',
        'request' => 'audit.controllers.AuditRequestController',
    );

    /**
     * @var array Map of model info including relations and behaviors.
     */
    public $modelMap = array();

    /**
     * @var array Defines the access filters for the module.
     * The default is AuditAccessFilter which will allow any user listed in AuditModule::adminUsers to have access.
     */
    public $controllerFilters = array(
        'auditAccess' => array('audit.components.AuditAccessFilter'),
    );

    /**
     * @var array A list of users who can access this module.
     */
    public $adminUsers = array();

    /**
     * Set to false if you do not wish to track database audits.
     * @var bool
     */
    public $enableAuditField = true;

    /**
     * @var array|string The view url for users, '--user_id--' will be replaced by the actual user_id.
     * <pre>
     * array('/user/view', 'id' => '--user_id--')
     * </pre>
     */
    public $userViewUrl;

    /**
     * @var array|string The home url, eg "/admin".
     */
    public $homeUrl;

    /**
     * @var string The path to YiiStrap.
     * Only required if you do not want YiiStrap in your app config, for example, if you are running YiiBooster.
     * Only required if you did not install using composer.
     * Please note:
     * - You must download YiiStrap even if you are using YiiBooster in your app.
     * - When using this setting YiiStrap will only loaded in the menu interface (eg: index.php?r=menu).
     */
    public $yiiStrapPath;

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
        return Yii::t('audit', 'Powered by {yii-audit-module}.', array('{yii-audit-module}' => '<a href="http://cornernote.github.io/yii-audit-module" rel="external">Yii Audit Module</a>'));
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return trim(file_get_contents(dirname(__FILE__) . '/version.txt'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Audit';
    }

    /**
     * Initializes the audit module.
     */
    public function init()
    {
        parent::init();

        // setup paths
        $this->setImport(array(
            'audit.models.*',
            'audit.components.*',
        ));

        // map models
        foreach ($this->getDefaultModelMap() as $method => $data)
            foreach ($data as $name => $options)
                if (empty($this->modelMap[$method][$name]))
                    $this->modelMap[$method][$name] = $options;

        // set homeUrl
        if ($this->homeUrl)
            Yii::app()->homeUrl = $this->homeUrl;

        // init yiiStrap
        $this->initYiiStrap();
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
     * @param null $labelPrefix
     * @return int|string
     */
    public function userViewLink($user_id, $labelPrefix = null)
    {
        if (!$this->userViewUrl)
            return $user_id;
        return str_replace('--user_id--', $user_id, CHtml::link($labelPrefix . '--user_id--', $this->userViewUrl));
    }

    /**
     * Setup yiiStrap, works even if YiiBooster is used in main app.
     */
    public function initYiiStrap()
    {
        // check that we are in a web application
        if (!(Yii::app() instanceof CWebApplication))
            return;
        // and in this module
        $route = explode('/', Yii::app()->getUrlManager()->parseUrl(Yii::app()->request));
        if ($route[0] != $this->id)
            return;
        // and yiiStrap is not configured
        if (Yii::getPathOfAlias('bootstrap') && file_exists(Yii::getPathOfAlias('bootstrap.helpers') . '/TbHtml.php'))
            return;
        // try to guess yiiStrapPath
        if ($this->yiiStrapPath === null)
            $this->yiiStrapPath = Yii::getPathOfAlias('vendor.crisu83.yiistrap');
        // check for valid path
        if (!realpath($this->yiiStrapPath))
            return;
        // setup yiiStrap components
        Yii::setPathOfAlias('bootstrap', realpath($this->yiiStrapPath));
        Yii::import('bootstrap.helpers.*');
        Yii::import('bootstrap.widgets.*');
        Yii::import('bootstrap.behaviors.*');
        Yii::import('bootstrap.form.*');
        Yii::app()->setComponents(array(
            'bootstrap' => array(
                'class' => 'bootstrap.components.TbApi',
            ),
        ), false);
    }

}
