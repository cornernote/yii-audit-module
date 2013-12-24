<?php

/**
 * SiteController for Tests
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class SiteController extends CController
{

    public function actionIndex()
    {
        echo 'Hello World';
    }

    public function actionUndefinedFunction()
    {
        undefined_function();
    }

    public function actionUndefinedVariable()
    {
        echo $undefined_variable;
    }

    public function actionPropertyOnNonObject()
    {
        $nonObject = 1;
        $nonObject->iAmNotAnObject;
    }

    public function actionMethodOnNonObject()
    {
        $nonObject = 1;
        $nonObject->iAmNotAnObject();
    }

}