<?php
/**
 * Archive Locations (archive-location)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\location\AdminController
 * @var $model ommu\archive\models\ArchiveLocation
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\archive\models\ArchiveLocation;
use ommu\archive\models\ArchiveStorage;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="archive-location-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->type == 'room') {
$js = <<<JS
	var depo;
	var v_depo = '$model->parent_id';
JS;
$this->registerJs($js, \yii\web\View::POS_END);

$parents = ArchiveLocation::getLocation(['publish'=>1, 'type'=>'building']);
$getDepoUrl = Url::to(['location/depo/suggest']);
echo $form->field($model, 'building')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a building..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a building..')], $parents),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
			'onChange' => new \yii\web\JsExpression('function(value) {
				if (!value.length) return;
				parent_id.disable(); 
				parent_id.clearOptions();
				parent_id.load(function(callback) {
					depo && depo.abort();
					depo = $.ajax({
						url: \''.$getDepoUrl.'\',
						data: {\'parent\': value},
						success: function(results) {
							parent_id.removeOption(v_depo);
							parent_id.showInput();
							parent_id.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
			}'),
		],
	])
	->label($model->getAttributeLabel('building'));
} ?>

<?php if($model->type != 'building') {
$parents = ArchiveLocation::getLocation(['publish'=>1, 'type'=>$model->type == 'depo' ? 'building' : 'depo']);
$depoPluginOptions = [
	'valueField' => 'id',
	'labelField' => 'label',
	'searchField' => ['label'],
	'persist' => false,
	'createOnBlur' => false,
	'create' => true,
];
if($model->type == 'room') {
	$depoPluginOptions = ArrayHelper::merge($depoPluginOptions, [
		'onChange' => new \yii\web\JsExpression('function(value) {
			v_depo = value;
		}'),
	]);
}
echo $form->field($model, 'parent_id')
	->widget(Selectize::className(), [
		'cascade' => $model->type == 'room' ? true : false,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('parent_id'))]),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('parent_id'))])], $parents),
		'pluginOptions' => $depoPluginOptions,
	])
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
		'items' => ArchiveStorage::getStorage(1),
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