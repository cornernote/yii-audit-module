<?php
/**
 * @var $this AuditLogController
 * @var $data AuditLog
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

echo '<div class="view">';

echo CHtml::tag('small', array(), Yii::t('audit', ':id :level on :date from :category by :user with :auditRequest', array(
    ':id' => CHtml::link(Yii::t('audit', 'Log ID-') . $data->id, array('log/view', 'id' => $data->id)),
    ':date' => Yii::app()->format->formatDatetime($data->created),
    ':level' => $data->level,
    ':category' => $data->category,
    ':user' => $this->module->userViewLink($data->user_id, 'User ID-'),
    ':auditRequest' => CHtml::link(Yii::t('audit', 'Request ID-') . $data->audit_request_id, array('request/view', 'id' => $data->audit_request_id)),
)));
echo '<br />' . CHtml::tag('small', array(), $data->getFileAlias());

echo CHtml::tag('pre', array(), CHtml::tag('small', array(), print_r($data->formatMessage(), true)));

echo '</div>';