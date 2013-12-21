<?php
/**
 * @var $this AuditWebController
 * @var $content string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
$this->beginContent('audit.views.layouts.main');
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