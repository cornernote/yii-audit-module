<?php

/**
 * Class AuditController
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditController extends AuditWebController
{

    /**
     * @param string $view the view to be rendered
     * @return bool
     */
    public function beforeRender($view)
    {
        if ($view != 'index')
            $this->addBreadcrumb(Yii::t('audit', 'Audit'), array('index'));

        return parent::beforeRender($view);
    }

    /**
     *
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}