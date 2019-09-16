<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="archive-setting-form">

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

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'reference_code_sikn')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('reference_code_sikn')); ?>

<?php echo $form->field($model, 'reference_code_separator')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('reference_code_separator')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'image_type')
	->textInput()
	->label($model->getAttributeLabel('image_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, bmp, gif, png"')); ?>

<?php echo $form->field($model, 'document_type')
	->textInput()
	->label($model->getAttributeLabel('document_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "pdf, doc, docx"')); ?>

<div class="ln_solid"></div>

<?php $fondSidkka = $model::getFondSidkkas();
echo $form->field($model, 'fond_sidkkas')
	->dropDownList($fondSidkka, ['prompt'=>''])
	->label($model->getAttributeLabel('fond_sidkkas')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->production_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
echo $form->field($model, 'production_date')
	->textInput(['type'=>'date'])
	->label($model->getAttributeLabel('production_date')); ?>

<div class="ln_solid"></div>

<?php $fondSidkka = $model::getFondSidkkas();
echo $form->field($model, 'maintenance_mode')
	->dropDownList($fondSidkka, ['prompt'=>''])
	->label($model->getAttributeLabel('maintenance_mode')); ?>

<?php echo $form->field($model, 'maintenance_image_path')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('maintenance_image_path')); ?>

<?php echo $form->field($model, 'maintenance_document_path')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('maintenance_document_path')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>