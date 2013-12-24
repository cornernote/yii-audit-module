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
$I->wantTo('ensure undefined variable is caught');

$I->amOnPage('site/undefinedVariable');
$I->see('PHP notice');
$I->see('Undefined variable: undefined_variable');
$I->canSeeInDatabase('audit_error', array(
    'code' => '500',
    'type' => 'PHP notice',
    'message' => 'Undefined variable: undefined_variable',
));