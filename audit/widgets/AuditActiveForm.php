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
     * Initializes the widget.
     */
    public function init()
    {
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = '';
        $this->htmlOptions['class'] .= 'form-horizontal';
        parent::init();
    }

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
     * @param null $label
     * @param array $options
     * @return string
     */
    public function getSubmitButton($label = null, $options = array())
    {
        if (!$label)
            $label = Yii::t('audit', 'Submit');
        $defaultOptions = array(
            'value' => $label,
            'type' => 'submit',
            'class' => 'btn btn-primary',
        );
        $options = CMap::mergeArray($defaultOptions, $options);
        return CHtml::tag('input', $options);
    }

    /**
     * @param null $label
     * @param array $options
     * @return string
     */
    public function getSubmitButtonRow($label = null, $options = array())
    {
        return CHtml::tag('div', array('class' => 'form-group'), CHtml::tag('div', array('class' => 'col-sm-offset-2 col-sm-10'), $this->getSubmitButton($label, $options)));
    }

    /**
     * @param $model
     * @param $attribute
     * @param array $htmlOptions
     * @return string
     */
    public function textFieldRow($model, $attribute, $htmlOptions = array())
    {
        // get label
        if (isset($htmlOptions['labelOptions'])) {
            $labelOptions = $htmlOptions['labelOptions'];
            unset($htmlOptions['labelOptions']);
        }
        if (!isset($labelOptions['class']))
            $labelOptions['class'] = '';
        $labelOptions['class'] .= ' col-sm-2 control-label';
        $label = $this->labelEx($model, $attribute, $labelOptions);

        // get input
        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = '';
        $htmlOptions['class'] .= ' form-control';
        $input = $this->textField($model, $attribute, $htmlOptions);

        return CHtml::tag('div', array(
            'class' => 'form-group',
        ), $label . CHtml::tag('div', array('class' => 'col-sm-10'), $input));
    }


    /**
     * @param $model
     * @param $attribute
     * @param array $htmlOptions
     * @return string
     */
    public function textAreaRow($model, $attribute, $htmlOptions = array())
    {
        // get label
        if (isset($htmlOptions['labelOptions'])) {
            $labelOptions = $htmlOptions['labelOptions'];
            unset($htmlOptions['labelOptions']);
        }
        if (!isset($labelOptions['class']))
            $labelOptions['class'] = '';
        $labelOptions['class'] .= ' col-sm-2 control-label';
        $label = $this->labelEx($model, $attribute, $labelOptions);

        // get input
        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = '';
        $htmlOptions['class'] .= ' form-control';
        $input = $this->textArea($model, $attribute, $htmlOptions);

        return CHtml::tag('div', array(
            'class' => 'form-group',
        ), $label . CHtml::tag('div', array('class' => 'col-sm-10'), $input));
    }

}