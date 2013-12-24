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
$I->wantTo('ensure method call on non-object is caught');

$I->amOnPage('site/methodOnNonObject');
$I->see('PHP error');
$I->see('Fatal error: Call to a member function iAmNotAnObject() on a non-object');
$I->canSeeInDatabase('audit_error', array(
    'code' => '500',
    'type' => 'PHP error',
    'message' => 'Fatal error: Call to a member function iAmNotAnObject() on a non-object',
));