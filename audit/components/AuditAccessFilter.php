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
        $user = $app->getUser();
        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : Yii::app()->request->userHostAddress;
        if (!$this->allowUser($audit, $user) || !$this->allowIp($audit, $ip)) {
            throw new CHttpException(403, 'You are not allowed to access this page.');
        }
        return parent::preFilter($filterChain);
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param $audit AuditModule
     * @param $user
     * @return bool whether the user IP is allowed by <a href='psi_element://ipFilters'>ipFilters</a>.
     * @throws CHttpException
     * @internal param string $ip the user IP
     */
    protected function allowUser($audit, $user)
    {
        if (empty($audit->adminUsers)) {
            return true;
        }
        if (in_array($user->getName(), $audit->adminUsers)) {
            return true;
        }
        return false;
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param $audit AuditModule
     * @param string $ip the user IP
     * @return bool whether the user IP is allowed by <a href='psi_element://ipFilters'>ipFilters</a>.
     */
    protected function allowIp($audit, $ip)
    {
        if (empty($audit->ipFilters))
            return true;
        foreach ($audit->ipFilters as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos)))
                return true;
        }
        return false;
    }

}
