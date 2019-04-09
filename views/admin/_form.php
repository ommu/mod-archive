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
use ommu\archive\models\ArchiveMedia;
use yii2mod\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="archives-form">

<?php
$js = <<<JS
	$('#shortcode').on('keyup', function (e) {
		var shortcode = $(this).val();
		if(shortcode == '')
			var shortcode = 'xxxx';
		$('#reference-code').html(shortcode);
	});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);

$form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]);

$wraper = [];
if(!$model->isNewRecord || ($model->isNewRecord && $parent))
	$wraper = ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']];?>

<?php //echo $form->errorSummary($model);?>

<?php 
$shortCode = $model->shortCode ? $model->shortCode : 'xxxx';
if(!$fond) {
	if(!$model->getErrors() && $parent)
		$model->parent_id = $parent->id;
	$parentCode = $model->parent->code;
	echo $form->field($model, 'parent_id', ArrayHelper::merge(['template' => '{label}{beginWrapper}{input}<h5 class="text-muted">'.$parentCode.$setting->reference_code_level_separator.'<span id="reference-code" class="text-primary">'.$shortCode.'</span></h5>{endWrapper}'], $wraper))
		->hiddenInput()
		->label($model->getAttributeLabel('code'));
} else {
	$model->level_id = 1;
	echo $form->field($model, 'level_id', ArrayHelper::merge(['template' => '{label}{beginWrapper}{input}<h5 class="text-muted">'.$setting->reference_code_sikn.$setting->reference_code_level_separator.'<span id="reference-code" class="text-primary">'.$shortCode.'</span></h5>{endWrapper}'], $wraper))
		->hiddenInput()
		->label($model->getAttributeLabel('code'));
} ?>

<?php echo $form->field($model, 'shortCode', $wraper)
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('shortCode'))
	->hint(Yii::t('app', 'Provide a specific local reference code, control number, or other unique identifier. The country and repository code will be automatically added from the linked repository record to form a full reference code.')); ?>

<?php echo $form->field($model, 'title', $wraper)
	->textarea(['rows'=>4, 'cols'=>50])
	->label($model->getAttributeLabel('title'))
	->hint(Yii::t('app', 'Provide either a formal title or a concise supplied title in accordance with the rules of multilevel description and national conventions.')); ?>

<?php if(!$fond) {
	$level = $model->isNewRecord ? $parent->getChildLevels(true) : $model->getChildLevels();
	echo $form->field($model, 'level_id', $wraper)
		->dropDownList($level, ['prompt'=>''])
		->label($model->getAttributeLabel('level_id'))
		->hint(Yii::t('app', 'Record the level of this unit of description.'));
} ?>

<?php echo $form->field($model, 'medium', $wraper)
	->textarea(['rows'=>2, 'cols'=>50])
	->label($model->getAttributeLabel('medium'))
	->hint(Yii::t('app', 'Record the extent of the unit of description by giving the number of physical or logical units in arabic numerals and the unit of measurement. Give the specific medium (media) of the unit of description. Separate multiple extents with a linebreak.')); ?>

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
	->hint(Yii::t('app', 'Record the name of the organization(s) or the individual(s) responsible for the creation, accumulation and maintenance of the records in the unit of description. Search for an existing name in the authority records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new authority record.')); ?>

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

<div class="ln_solid"></div>

<?php $publish = Archives::getPublish();
echo $form->field($model, 'publish', $wraper)
	->dropDownList($publish, ['prompt' => ''])
	->label($model->getAttributeLabel('publish')); ?>

<?php if($fond || $model->level->sidkkas) {
	echo $form->field($model, 'sidkkas', $wraper)
		->checkbox()
		->label($model->getAttributeLabel('sidkkas'));
} ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="<?php echo empty($wraper) ? 'col-md-6 col-sm-9' : 'col-sm-9';?> col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>