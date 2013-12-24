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
$I->wantTo('ensure http error 404 is caught');

$I->amOnPage('site/httpError404');
$I->see('Page Not Found');
$I->see('The requested URL was not found on this server.');
$I->canSeeInDatabase('audit_error', array(
    'code' => '404',
    'type' => 'CHttpException',
    'message' => 'Page not found message.',
));