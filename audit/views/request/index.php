<?php
/**
 * @var $this AuditRequestController
 * @var $auditRequest AuditRequest
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

Yii::app()->user->setState('index.auditRequest', Yii::app()->request->requestUri);
$this->pageTitle = Yii::t('audit', 'Requests');

// links
$this->menu[] = array('label' => Yii::t('audit', 'Search'), 'url' => '#', 'linkOptions' => array('class' => 'auditRequest-grid-search btn btn-default'));
if (Yii::app()->user->getState('index.auditRequest') != $this->createUrl('index'))
    $this->menu[] = array('label' => Yii::t('audit', 'Reset Filters'), 'url' => array('index'), 'linkOptions' => array('class' => 'btn btn-default'));

// search
$this->renderPartial('_search', array(
    'auditRequest' => $auditRequest,
));

// grid
$this->renderPartial('_grid', array(
    'auditRequest' => $auditRequest,
));