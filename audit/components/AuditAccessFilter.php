<?php

/**
 * AuditAccessFilter
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditAccessFilter extends CFilter
{

    /**
     * @param CFilterChain $filterChain
     * @return bool
     * @throws CHttpException
     */
    protected function preFilter($filterChain)
    {
        $app = Yii::app();
        /** @var AuditModule $audit */
        $audit = $app->getModule('audit');
        if (!in_array($app->getUser()->getName(), $audit->adminUsers))
            throw new CHttpException(403, 'You are not allowed to access this page.');
        return parent::preFilter($filterChain);
    }

}
