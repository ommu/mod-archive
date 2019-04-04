<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveLevel;
use ommu\archive\models\ArchiveMedia;
use yii2mod\selectize\Selectize;
?>

<div class="archives-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]);

// echo '<pre>';
// print_r($model);

$wraper = [];
if(!$model->isNewRecord || ($model->isNewRecord && $parent))
	$wraper = ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']];?>

<?php //echo $form->errorSummary($model);?>

<?php if(!$fond) {
	if(!$model->getErrors() && $parent)
		$model->parent_id = $parent;
	echo $form->field($model, 'parent_id', $wraper)
		->textInput(['type'=>'number', 'min'=>'1'])
		->label($model->getAttributeLabel('parent_id'));
} ?>

<?php echo $form->field($model, 'code', $wraper)
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('code'))
	->hint(Yii::t('app', 'Provide a specific local reference code, control number, or other unique identifier. The country and repository code will be automatically added from the linked repository record to form a full reference code. (ISAD 3.1.1)')); ?>

<?php echo $form->field($model, 'title', $wraper)
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('title'))
	->hint(Yii::t('app', 'Provide either a formal title or a concise supplied title in accordance with the rules of multilevel description and national conventions. (ISAD 3.1.2)')); ?>

<?php if($fond) {
	$model->level_id = 1;
	echo $form->field($model, 'level_id', ['template' => '{input}', 'options' => ['tag' => null]])
		->hiddenInput();
} else {
	$level = $model->getChildLevel();
	echo $form->field($model, 'level_id', $wraper)
		->dropDownList($level, ['prompt'=>''])
		->label($model->getAttributeLabel('level_id'))
		->hint(Yii::t('app', 'Record the level of this unit of description. (ISAD 3.1.4)'));
} ?>

<div class="ln_solid"></div>

<?php 
$creatorSuggestUrl = Url::to(['setting/creator/suggest']);
echo $form->field($model, 'creator', $wraper)
	->widget(Selectize::className(), [
		'url' => $creatorSuggestUrl,
		'pluginOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('creator'))
	->hint(Yii::t('app', 'Record the name of the organization(s) or the individual(s) responsible for the creation, accumulation and maintenance of the records in the unit of description. Search for an existing name in the authority records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new authority record. (ISAD 3.2.1)')); ?>

<?php
$repositorySuggestUrl = Url::to(['setting/repository/suggest']);
echo $form->field($model, 'repository', $wraper)
	->widget(Selectize::className(), [
		'url' => $repositorySuggestUrl,
		'pluginOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('repository'))
	->hint(Yii::t('app', 'Record the name of the organization which has custody of the archival material. Search for an existing name in the archival institution records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new archival institution record.')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'media', $wraper)
	->widget(Selectize::className(), [
		'items' => ArchiveMedia::getMedia(1),
		'options' => [
			'multiple' => true,
		],
		'pluginOptions' => [
			'plugins' => ['remove_button'],
		],
	])
	->label($model->getAttributeLabel('media')); ?>

<?php if($item || $model->level->image_type) {
	$imageType = Archives::getImageType();
	echo $form->field($model, 'image_type', $wraper)
		->radioList($imageType, ['prompt' => ''])
		->label($model->getAttributeLabel('image_type'));
} ?>

<?php if($fond || $model->level->sidkkas) {
	echo $form->field($model, 'sidkkas', $wraper)
		->checkbox()
		->label($model->getAttributeLabel('sidkkas'));
} ?>

<?php echo $form->field($model, 'publish', $wraper)
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="<?php echo empty($wraper) ? 'col-md-6 col-sm-9' : 'col-sm-9';?> col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>