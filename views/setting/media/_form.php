<?php
/**
 * Archive Media (archive-media)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\MediaController
 * @var $model ommu\archive\models\ArchiveMedia
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 02:34 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="archive-media-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
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

<?php echo $form->field($model, 'media_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('media_name_i')); ?>

<?php echo $form->field($model, 'media_desc_i')
	->textarea(['rows'=>4, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('media_desc_i')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>