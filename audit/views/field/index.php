<?php
/**
 * @var $this AuditFieldController
 * @var $auditField AuditField
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

Yii::app()->user->setState('index.auditField', Yii::app()->request->requestUri);
$this->pageTitle = Yii::t('audit', 'Fields');

echo '<div>';
echo CHtml::link(Yii::t('audit', 'Search'), '#', array('class' => 'auditField-grid-search'));
if (Yii::app()->user->getState('index.auditField') != $this->createUrl('index')) {
    echo ' ' . CHtml::link(Yii::t('audit', 'Reset Filters'), array('index'));
}
echo '</div>';

// search
$this->renderPartial('_search', array(
    'auditField' => $auditField,
));

// grid
$this->renderPartial('_grid', array(
    'auditField' => $auditField,
));