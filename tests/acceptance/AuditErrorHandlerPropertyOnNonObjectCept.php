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
$I->wantTo('ensure property call on non-object is caught');

$I->amOnPage('site/propertyOnNonObject');
$I->see('PHP notice');
$I->see('Trying to get property of non-object');
$I->canSeeInDatabase('audit_error', array(
    'code' => '500',
    'type' => 'PHP notice',
    'message' => 'Trying to get property of non-object',
));