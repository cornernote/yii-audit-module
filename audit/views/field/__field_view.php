<?php
/**
 * @var $this CController
 * @var $data AuditField
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

echo '<tr>';

echo '<td>';
echo isset($data->old_value) ? $data->old_value : '&nbsp;';
echo isset($data->old_value) && isset($data->new_value) ? ' &gt; ' : '';
echo isset($data->new_value) ? $data->new_value : '&nbsp;';
echo '</td>';

echo '<td>';
echo Yii::app()->dateFormatter->formatDatetime($data->created);
echo '</td>';

echo '<td>';
echo '<small>' . $data->created . '</small>';
echo '</td>';

echo '<td>';
if (Yii::app()->user->checkAccess('admin')) {
    echo ($data->user_id && is_numeric($data->user_id) ? User::model()->findByPk($data->user_id)->getLink() : $data->user_id);
}
else {
    echo ($data->user_id && is_numeric($data->user_id) ? User::model()->findByPk($data->user_id)->name : $data->user_id);
}
echo '</td>';

if (Yii::app()->user->checkAccess('admin')) {
    echo '<td>';
    echo $data->audit ? $data->audit->getLink() : '';
    echo '</td>';
}

echo '</tr>';
