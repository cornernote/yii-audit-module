<?php

/**
 * AuditFieldController
 *
 * @method AuditField loadModel() loadModel($id, $model = null)
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditFieldController extends AuditWebController
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
            $this->addBreadcrumb(Yii::t('audit', 'Fields'), Yii::app()->user->getState('index.auditField', array('field/index')));

        return true;
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $auditField = new AuditField('search');
        if (!empty($_GET['AuditField']))
            $auditField->attributes = $_GET['AuditField'];

        $this->render('index', array(
            'auditField' => $auditField,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        $auditField = $this->loadModel($id);

        $this->render('view', array(
            'auditField' => $auditField,
        ));
    }

}
