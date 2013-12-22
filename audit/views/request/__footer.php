<?php
/**
 * @var $this CController
 * @var $color string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$output = array();
$app = Yii::app();
/** @var AuditModule $audit */
$audit = $app->getModule('audit');
$isAdmin = in_array($app->getUser()->name, $audit->adminUsers);
/** @var AuditErrorHandler $errorHandler */
$errorHandler = $app->getErrorHandler();
if ($errorHandler->hasAuditRequest()) {
    $auditRequest = $errorHandler->getAuditRequest();
    if ($isAdmin)
        $output[] = CHtml::link($auditRequest->id, array('/' . $audit->id . '/request/view', 'id' => $auditRequest->id));
    else
        $output[] = $auditRequest->id;
}
$output[] = number_format(microtime(true) - YII_BEGIN_TIME, 2) . 's';
$output[] = round(memory_get_peak_usage() / 1024 / 1024, 1) . 'm';
$output[] = date('Y-m-d H:i:s');

echo CHtml::tag('span', array(
    'style' => 'color:' . (isset($color) ? $color : (YII_DEBUG || $isAdmin ? 'inherit' : 'transparent')) . ';',
), implode(' | ', $output));
