<?php

/**
 * AuditErrorController
 *
 * @method AuditError loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditErrorController extends AuditWebController
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
            $this->addBreadcrumb(Yii::t('audit', 'Errors'), Yii::app()->user->getState('index.auditError', array('error/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $auditError = new AuditError('search');
        if (!empty($_GET['AuditError']))
            $auditError->attributes = $_GET['AuditError'];

        $this->render('index', array(
            'auditError' => $auditError,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $auditError = $this->loadModel($id);

        $this->render('view', array(
            'auditError' => $auditError,
        ));
    }

}

