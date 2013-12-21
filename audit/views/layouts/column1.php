<?php
/**
 * @var $this EmailWebController
 * @var $content string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
$this->beginContent('email.views.layouts.main');
?>
    <div class="container">
        <div id="content">
            <?php
            echo CHtml::tag('h1', array(), $this->pageHeading);
            echo $this->renderBreadcrumbs();
            echo $content;
            ?>
        </div>
    </div>
<?php
$this->endContent();