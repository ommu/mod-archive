<?php
/**
 * Archive Levels (archive-level)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\LevelController
 * @var $model ommu\archive\models\ArchiveLevel
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 02:27 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\archive\models\ArchiveLevel;
?>

<div class="archive-level-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'level_name_i')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('level_name_i')); ?>

<?php echo $form->field($model, 'level_desc_i')
	->textarea(['rows' => 4, 'cols' => 50])
	->label($model->getAttributeLabel('level_desc_i')); ?>

<hr/>

<?php $child = ArchiveLevel::getLevel(1);
unset($child[$model->id]);
echo $form->field($model, 'child')
	->checkboxList($child)
	->label($model->getAttributeLabel('child')); ?>

<hr/>

<?php $field = ArchiveLevel::getField();
echo $form->field($model, 'field')
	->checkboxList($field)
	->label($model->getAttributeLabel('field')); ?>

<hr/>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>