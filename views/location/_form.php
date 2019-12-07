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

<?php if(in_array($model->type, ['room', 'rack'])) {
$js = <<<JS
	var depo;
	var v_depo = '$model->parent_id';
JS;
$this->registerJs($js, \yii\web\View::POS_END);

$type = 'building';
$buildingUrl = Url::to(['location/depo/suggest']);
if($model->type == 'rack') {
	$type = 'depo';
	$buildingUrl = Url::to(['location/room/suggest']);
}
$parents = ArchiveLocation::getLocation(['publish'=>1, 'type'=>$type, 'isDepo'=>($type == 'depo' ? true : false)]);
echo $form->field($model, 'building')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('building'))]),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('building'))])], $parents),
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => $model->type == 'rack' ? false : true,
			'onChange' => new \yii\web\JsExpression('function(value) {
				if (!value.length) return;
				parent_id.disable(); 
				parent_id.clearOptions();
				parent_id.load(function(callback) {
					depo && depo.abort();
					depo = $.ajax({
						url: \''.$buildingUrl.'\',
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
if($model->type == 'depo')
	$type = 'building';
else if($model->type == 'room')
	$type = 'depo';
else if($model->type == 'rack')
	$type = 'room';
$parents = ArchiveLocation::getLocation(['publish'=>1, 'type'=>$type]);
$parentPluginOptions = [
	'valueField' => 'id',
	'labelField' => 'label',
	'searchField' => ['label'],
	'persist' => false,
	'createOnBlur' => false,
	'create' => $model->type == 'rack' ? false : true,
];
if(in_array($model->type, ['room', 'rack'])) {
	$parentPluginOptions = ArrayHelper::merge($parentPluginOptions, [
		'onChange' => new \yii\web\JsExpression('function(value) {
			v_depo = value;
		}'),
	]);
}
echo $form->field($model, 'parent_id')
	->widget(Selectize::className(), [
		'cascade' => in_array($model->type, ['room', 'rack']) ? true : false,
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('parent_id'))]),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a {type}..', ['type' => strtolower($model->getAttributeLabel('parent_id'))])], $parents),
		'pluginOptions' => $parentPluginOptions,
	])
	->label($model->getAttributeLabel('parent_id'));
} ?>

<?php echo $form->field($model, 'location_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('location_name')); ?>

<?php if($model->type != 'rack') {
echo $form->field($model, 'location_desc')
	->textarea(['rows'=>4, 'cols'=>50])
	->label($model->getAttributeLabel('location_desc'));
} ?>

<?php if(in_array($model->type, ['room', 'rack'])) {
$pluginOptions = [];
if($model->type == 'room') {
	$pluginOptions = [
		'plugins' => ['remove_button'],
	];
}
echo $form->field($model, 'storage')
	->widget(Selectize::className(), [
		'items' => ArchiveStorage::getStorage(1),
		'options' => [
			'multiple' => $model->type == 'room' ? true : false,
		],
		'pluginOptions' => $pluginOptions,
	])
	->label($model->getAttributeLabel('storage'));
} ?>

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