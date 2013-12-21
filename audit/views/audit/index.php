<?php
/**
 * @var $this AuditController
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$this->pageTitle = Yii::t('audit', 'Welcome to the Yii Audit Module!');

echo CHtml::tag('p', array(), Yii::t('audit', 'You may use the following auditing tools to help manage your Yii application.'));
$this->widget('zii.widgets.CMenu', array(
    'items' => array(
        array('label' => Yii::t('audit', 'Errors'), 'url' => array('error/index')),
        array('label' => Yii::t('audit', 'Fields'), 'url' => array('field/index')),
        array('label' => Yii::t('audit', 'Logs'), 'url' => array('log/index')),
        array('label' => Yii::t('audit', 'Requests'), 'url' => array('request/index')),
    ),
));
