<?php
/**
 * Archive Repositories (archive-repository)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\RepositoryController
 * @var $model ommu\archive\models\ArchiveRepository
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 April 2019, 15:06 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="archive-repository-form">

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

<?php echo $form->field($model, 'repository_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('repository_name')); ?>

<?php echo $form->field($model, 'repository_desc')
	->textarea(['rows'=>4, 'cols'=>50])
	->label($model->getAttributeLabel('repository_desc')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>