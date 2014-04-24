<?php

/**
 * AuditErrorEmailCommand
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditErrorEmailCommand extends CConsoleCommand
{

    /**
     * @var null|string|array The emails to send errors to.
     */
    public $email;

    /**
     * @var bool
     */
    public $secureUrl = false;

    /**
     * Send error emails.
     * Requires yii-email-module
     */
    public function actionIndex()
    {
        /** @var AuditModule $audit */
        $audit = Yii::app()->getModule('audit');
        $data = $audit->getDbConnection()->createCommand("SELECT id FROM " . AuditError::model()->tableName() . " WHERE status=:new_status")->query(array(
            ':new_status' => 'new',
        ));
        while ($data && ($row = $data->read()) !== false) {
            $auditError = AuditError::model()->findByPk($row['id']);
            $message = Yii::t('app', '<b>:message</b><br />in <i>:file</i> on line <i>:line</i>.<br/>:link.', array(
                ':message' => $auditError->message,
                ':file' => $auditError->file,
                ':line' => $auditError->line,
                ':link' => CHtml::link(Yii::t('audit', 'view'), Yii::app()->createAbsoluteUrl('audit/error/view', array('id' => $auditError->id), $this->secureUrl ? 'https' : 'http')),
            ));
            Yii::app()->emailManager->email($this->email, $auditError->type . ' (' . $auditError->hash . ')', $message);
            $auditError->status = 'emailed';
            $auditError->save(false, array('status'));
        }
    }

}
