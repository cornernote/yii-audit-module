<?php

/**
 * AuditLogController
 *
 * @method AuditLog loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditLogController extends AuditWebController
{
    /**
     * @param string $view the view to be rendered
     * @return bool
     */
    public function beforeRender($view)
    {
        if (!parent::beforeRender($view))
            return false;
        if ($view != 'index')
            $this->addBreadcrumb(Yii::t('audit', 'Logs'), Yii::app()->user->getState('index.auditLog', array('log/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $auditLog = new AuditLog('search');
        if (!empty($_GET['AuditLog']))
            $auditLog->attributes = $_GET['AuditLog'];

        $this->render('index', array(
            'auditLog' => $auditLog,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $auditLog = $this->loadModel($id);

        $this->render('view', array(
            'auditLog' => $auditLog,
        ));
    }

}
