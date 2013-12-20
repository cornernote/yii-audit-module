<?php
/**
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
$cs = Yii::app()->clientScript;
$cs->coreScriptPosition = CClientScript::POS_HEAD;
$cs->scriptMap = array();
$baseUrl = $this->module->assetsUrl;
$cs->registerCoreScript('jquery');

$cs->registerCssFile($baseUrl . '/css/screen.css', 'screen, projection');
$cs->registerCssFile($baseUrl . '/css/print.css', 'print');
$cs->registerCssFile($baseUrl . '/css/main.css');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">
    <div id="header">
        <div class="top-menus">
            <?php
            $this->widget('zii.widgets.CBreadcrumbs', array(
                'links' => array(
                    Yii::t('audit', 'Errors') => Yii::app()->user->getState('index.auditError', array('error/index')),
                    Yii::t('audit', 'Fields') => Yii::app()->user->getState('index.auditField', array('field/index')),
                    Yii::t('audit', 'Requests') => Yii::app()->user->getState('index.auditRequest', array('request/index')),
                ),
                'separator' => ' | ',
            ));
            ?>
        </div>
        <div id="logo"><?php echo CHtml::link(CHtml::image($baseUrl . '/images/logo.png'), array('audit/index')); ?></div>
    </div>

    <?php echo $content; ?>

</div>

<div id="footer">
    <?php $this->renderPartial('audit.views.request.__footer'); ?>
    <br/><?php echo AuditModule::powered(); ?>
    <br/>A product of <a href="http://mrphp.com.au">Mr PHP</a>.
</div>

</body>
</html>