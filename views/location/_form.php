<?php
/**
 * Archive Locations (archive-location)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\location\AdminController
 * @var $model ommu\archive\models\ArchiveLocation
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\archive\models\ArchiveLocation;
use ommu\archive\models\ArchiveStorage;
use yii2mod\selectize\Selectize;
?>

<div class="archive-location-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]);?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->type != 'building') {
$parentId = ArchiveLocation::getLocation(['publish'=>1, 'type'=>$model->type == 'depo' ? 'building' : 'depo']);
echo $form->field($model, 'parent_id')
	->dropDownList($parentId, ['prompt' => ''])
	->label($model->getAttributeLabel('parent_id'));
} ?>

<?php echo $form->field($model, 'location_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('location_name')); ?>

<?php echo $form->field($model, 'location_desc')
	->textarea(['rows'=>4, 'cols'=>50])
	->label($model->getAttributeLabel('location_desc')); ?>

<?php if($model->type == 'room') {
echo $form->field($model, 'storage')
	->widget(Selectize::className(), [
		'items' =>ArchiveStorage::getStorage(1),
		'options' => [
			'multiple' => true,
		],
		'pluginOptions' => [
			'plugins' => ['remove_button'],
		],
	])
	->label($model->getAttributeLabel('storage'));
} ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>