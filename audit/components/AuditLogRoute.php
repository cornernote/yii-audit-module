<?php
/**
 * AuditLogRoute
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditLogRoute extends CLogRoute
{

    /**
     * @param array $logs
     */
    protected function processLogs($logs)
    {
        $auditLogs = array();
        $auditRequestId = $this->getAuditRequestId();
        $userId = Yii::app()->hasComponent('user') ? Yii::app()->user->id : 0;
        $audit = Yii::app()->getModule('audit');
        $commandBuilder = $audit->getDbConnection()->getCommandBuilder();
        foreach ($logs as $log) {
            $message = explode("\n", $log[0]);
            $file = count($message) > 1 ? array_pop($message) : '';
            $message = implode("\n", $message);
            $auditLogs[] = array(
                'level' => $log[1],
                'category' => $log[2],
                'message' => AuditHelper::pack($message),
                'file' => $file,
                'audit_request_id' => $auditRequestId,
                'user_id' => $userId,
                'created' => (int)$log[3],
            );
            // save 100 rows at a time, more than this causes an issue
            if (count($auditLogs) > 100) {
                $commandBuilder->createMultipleInsertCommand(AuditLog::model()->tableName(), $auditLogs)->execute();
                $auditLogs = array();
            }
        }
        if ($auditLogs) {
            $commandBuilder->createMultipleInsertCommand(AuditLog::model()->tableName(), $auditLogs)->execute();
        }
    }

    /**
     * @return int
     */
    protected function getAuditRequestId()
    {
        if (Yii::app()->getErrorHandler() instanceof AuditErrorHandler) {
            $auditRequest = Yii::app()->getErrorHandler()->getAuditRequest();
            if ($auditRequest)
                return $auditRequest->id;
        }
        return 0;
    }

}
