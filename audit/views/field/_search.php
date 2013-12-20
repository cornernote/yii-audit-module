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

/** @var AuditActiveForm $form */
$form = $this->beginWidget('audit.widgets.AuditActiveForm', array(
    'method' => 'get',
    'htmlOptions' => array('class' => 'hide'),
));
$form->searchToggle('auditField-grid-search', 'auditField-grid');

echo '<fieldset>';
echo '<legend>' . Yii::t('audit', 'Search') . '</legend>';
echo $form->textFieldRow($auditField, 'id');
echo $form->textFieldRow($auditField, 'audit_request_id');
echo $form->textFieldRow($auditField, 'user_id');
echo $form->textFieldRow($auditField, 'old_value');
echo $form->textFieldRow($auditField, 'new_value');
echo $form->textFieldRow($auditField, 'action');
echo $form->textFieldRow($auditField, 'model_name');
echo $form->textFieldRow($auditField, 'model_id');
echo $form->textFieldRow($auditField, 'field');
echo $form->textFieldRow($auditField, 'created');
echo '</fieldset>';

echo $form->getSubmitButtonRow(Yii::t('audit', 'Search'));

$this->endWidget();