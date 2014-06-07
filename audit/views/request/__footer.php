<?php
/**
 * @var $this CController
 * @var $tag string
 * @var $htmlOptions array
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$app = Yii::app();
/** @var AuditModule $audit */
$audit = $app->getModule('audit');
$isAdmin = in_array($app->getUser()->name, $audit->adminUsers);

if (!isset($tag)) {
    $tag = 'span';
}
if (!isset($showDate)) {
    $showDate = true;
}
if (!isset($contentBefore)) {
    $contentBefore = '';
}
if (!isset($contentAfter)) {
    $contentAfter = '';
}
if (!isset($htmlOptions)) {
    $htmlOptions = array(
        'style' => 'color:' . (isset($color) ? $color : (YII_DEBUG || $isAdmin ? 'inherit' : 'transparent')) . ';',
    );
}

/** @var AuditErrorHandler $errorHandler */
$errorHandler = $app->getErrorHandler();

$output = array();
if ($errorHandler->hasAuditRequest()) {
    $auditRequest = $errorHandler->getAuditRequest();
    if ($isAdmin)
        $output[] = CHtml::link($auditRequest->id, array('/' . $audit->id . '/request/view', 'id' => $auditRequest->id));
    else
        $output[] = $auditRequest->id;
}
if ($showDate) {
    $output[] = Yii::app()->dateFormatter->formatDatetime(time());
}
$output[] = number_format(microtime(true) - YII_BEGIN_TIME, 2) . 's';
$output[] = round(memory_get_peak_usage() / 1024 / 1024, 1) . 'm';

echo CHtml::tag($tag, $htmlOptions, $contentBefore . implode(' | ', $output) . $contentAfter);
