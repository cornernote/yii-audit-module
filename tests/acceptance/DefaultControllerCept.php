<?php
/**
 * DefaultController Test
 *
 * @var $scenario \Codeception\Scenario
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */

$I = new WebGuy($scenario);
$I->wantTo('ensure DefaultController works');

$I->amOnPage('email');
$I->see('You may use the following tools to help manage email within your application.');

$I->click('Spool');
$I->see('Spools');

$I->click('Template');
$I->see('Templates');

$I->click('Email');
$I->see('You may use the following tools to help manage email within your application.');
