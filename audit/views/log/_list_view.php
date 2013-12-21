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
    ':date' => date('Y-m-d H:i:s', $data->created),
    ':level' => $data->level,
    ':category' => $data->category,
    ':user' => $this->module->userViewLink($data->user_id, 'User ID-'),
    ':auditRequest' => CHtml::link(Yii::t('audit', 'Request ID-') . $data->audit_request_id, array('request/view', 'id' => $data->audit_request_id)),
)));
echo '<br />' . CHtml::tag('small', array(), $data->getFileAlias());

//echo '<b>' . CHtml::encode($data->getAttributeLabel('id')) . ':</b>';
//echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)) . '<br />';

echo '<pre><small>' . print_r($data->formatMessage(), true) . '</small></pre>';

echo '</div>';