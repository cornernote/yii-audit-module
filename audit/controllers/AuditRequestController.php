<?php

/**
 * AuditRequestController
 *
 * @method AuditRequest loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditRequestController extends AuditWebController
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
            $this->addBreadcrumb(Yii::t('audit', 'Requests'), Yii::app()->user->getState('index.auditRequest', array('request/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $auditRequest = new AuditRequest('search');
        if (!empty($_GET['AuditRequest']))
            $auditRequest->attributes = $_GET['AuditRequest'];

		// TODO, this allows better searching
        //Yii::app()->getUrlManager()->setUrlFormat('get');

        $this->render('index', array(
            'auditRequest' => $auditRequest,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $auditRequest = $this->loadModel($id);

        $this->render('view', array(
            'auditRequest' => $auditRequest,
        ));
    }

}
