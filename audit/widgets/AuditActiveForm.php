<?php

/**
 * AuditActiveForm
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditActiveForm extends CActiveForm
{

    /**
     * @param $buttonId
     * @param $hiderId
     */
    public function searchToggle($buttonClass, $gridId = null)
    {
        $script = "$('." . $buttonClass . "').click(function(){ $('#" . $this->id . "').toggle(); });";
        if ($gridId) {
            $script .= "
                $('#" . $this->id . "').submit(function(){
                    $.fn.yiiGridView.update('" . $gridId . "', {url: $(this).attr('action'),data: $(this).serialize()});
                    return false;
                });
            ";
        }
        Yii::app()->clientScript->registerScript($this->id . '-searchToggle', $script, CClientScript::POS_READY);
    }


    /**
     * @param string $label
     * @return string
     */
    public function getSubmitButton($label = null, $options = array())
    {
        if (!$label)
            $label = Yii::t('email', 'Submit');
        $defaultOptions = array(
            'value' => $label,
        );
        $options = CMap::mergeArray($defaultOptions, $options);
        return CHtml::tag('submit', $options);
    }

    /**
     * @param string $label
     * @return string
     */
    public function getSubmitButtonRow($label = null, $options = array())
    {
        echo CHtml::tag('div', array(), $this->getSubmitButton($label, $options));
    }

    /**
     * @param $model CActiveRecord
     * @param $attribute string
     */
    public function textFieldRow($model, $attribute, $htmlOptions = array())
    {
        $labelOptions = array();
        if (isset($htmlOptions['labelOptions'])) {
            $labelOptions = $htmlOptions['labelOptions'];
            unset($htmlOptions['labelOptions']);
        }
        $contents = $this->labelEx($model, $attribute, $labelOptions) . $this->textField($model, $attribute, $htmlOptions);
        echo CHtml::tag('div', array(), $contents);
    }

}