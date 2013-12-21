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
        if ($view != 'index')
            $this->addBreadcrumb(Yii::t('audit', 'Errors'), Yii::app()->user->getState('index.auditError', array('error/index')));

        return parent::beforeRender($view);
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

    /**
     * @param $error
     * @param null $archive
     */
    public function actionView2($error, $archive = null)
    {
        $path = app()->getRuntimePath() . '/errors/';
        if ($archive)
            $path .= 'archive/';
        $path .= $error;

        $auditInfo = str_replace(array('archive/', 'audit-', '.html'), '', $error);
        $errorCode = '';
        if (strpos($auditInfo, '-')) {
            list($auditId, $errorCode) = explode('-', $auditInfo);
            if ($errorCode) {
                $errorCode = $errorCode . ' - ';
            }
        }
        else {
            $auditId = $auditInfo;
        }
        $audit = Audit::model()->findByPk($auditId);
        $auditLink = '';
        if ($audit) {
            $auditLink = $audit->getLink() . ' ';
        }
        $contents = file_get_contents($path);
        $contents = str_replace('class="container"', 'class="container-fluid"', $contents);
        if (strpos($contents, '<body>')) {
            $contents = str_replace('</h1>', ' - ' . $errorCode . $auditId . '</h1>' . '<h3> ' . $auditLink . ' logged on ' . date('Y-m-d H:i:s', filemtime($path)) . '</h3>', $contents);
            $contents = StringHelper::getBetweenString($contents, '<body>', '</body>');
            Yii::app()->clientScript->registerCss('error', file_get_contents(dirname($this->getViewFile('index')) . '/view.css'));
        }
        else {
            $contents = '<h1>' . $errorCode . $auditId . '</h1>' . '<h3> ' . $auditLink . ' logged on ' . date('Y-m-d H:i:s', filemtime($path)) . '</h3><pre>' . $contents . '</pre>';
        }
        $this->pageTitle = Yii::t('audit', 'Error') . ' ' . $error;
        $this->renderText($contents);
        app()->end();
    }

}

