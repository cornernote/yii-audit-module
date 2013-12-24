<?php
/**
 *
 * @var $scenario \Codeception\Scenario
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$I = new WebGuy($scenario);
$I->wantTo('ensure http error 403 is caught');

$I->amOnPage('site/httpError403');
$I->see('Unauthorized');
$I->see('You do not have the proper credential to access this page.');
$I->canSeeInDatabase('audit_error', array(
    'code' => '403',
    'type' => 'CHttpException',
    'message' => 'Unauthorized message.',
));