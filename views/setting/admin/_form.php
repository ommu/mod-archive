<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\flatpickr\Flatpickr;
?>

<div class="archive-setting-form">

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

<?php
if ($model->isNewRecord && !$model->getErrors()) {
	$model->license = $model->licenseCode();
}
echo $form->field($model, 'license')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<hr/>

<?php echo $form->field($model, 'reference_code_sikn')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('reference_code_sikn')); ?>

<?php echo $form->field($model, 'reference_code_separator')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('reference_code_separator')); ?>

<?php $fondSidkkas = $model::getFondSidkkas();
echo $form->field($model, 'short_code')
	->dropDownList($fondSidkkas, ['prompt' => ''])
	->label($model->getAttributeLabel('short_code')); ?>

<?php $fondSidkkas = $model::getFondSidkkas();
echo $form->field($model, 'medium_sublevel')
	->dropDownList($fondSidkkas, ['prompt' => ''])
	->label($model->getAttributeLabel('medium_sublevel')); ?>

<hr/>

<?php echo $form->field($model, 'image_type')
	->textInput()
	->label($model->getAttributeLabel('image_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "jpg, jpeg, bmp, gif, png"')); ?>

<?php echo $form->field($model, 'document_type')
	->textInput()
	->label($model->getAttributeLabel('document_type'))
	->hint(Yii::t('app', 'pisahkan jenis file dengan koma (,). example: "pdf, doc, docx"')); ?>

<hr/>

<?php echo $form->field($model, 'fond_sidkkas')
	->dropDownList($fondSidkkas, ['prompt' => ''])
	->label($model->getAttributeLabel('fond_sidkkas')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->production_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
}
echo $form->field($model, 'production_date')
    ->widget(Flatpickr::className(), ['model' => $model, 'attribute' => 'production_date'])
	->label($model->getAttributeLabel('production_date')); ?>

<hr/>

<?php echo $form->field($model, 'maintenance_mode')
	->dropDownList($fondSidkkas, ['prompt' => ''])
	->label($model->getAttributeLabel('maintenance_mode')); ?>

<?php echo $form->field($model, 'maintenance_image_path')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('maintenance_image_path')); ?>

<?php echo $form->field($model, 'maintenance_document_path')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('maintenance_document_path')); ?>

<hr/>

<?php $breadcrumbAppsName = $form->field($model, 'breadcrumb_param[name]', ['template' => '{beginWrapper}<div class="h6 mt-0 mb-4">App Name</div>{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-4 col-xs-6 col-sm-offset-3 mt-4'], 'options' => ['tag' => null]])
	->label($model->getAttributeLabel('breadcrumb_param[name]')); ?>

<?php $breadcrumbAppsUrl = $form->field($model, 'breadcrumb_param[url]', ['template' => '{beginWrapper}<div class="h6 mt-0 mb-4">App URL</div>{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-5 col-xs-6 mt-4'], 'options' => ['tag' => null]])
	->label($model->getAttributeLabel('breadcrumb_param[url]')); ?>

<?php $status = $model::getBreadcrumbStatus();
echo $form->field($model, 'breadcrumb_param[status]', ['template' => '{label}{beginWrapper}<div class="h6 mt-4 mb-4">Status</div>{input}{endWrapper}'.$breadcrumbAppsName.$breadcrumbAppsUrl.'{error}{hint}', 'horizontalCssClasses' => ['error' => 'col-sm-6 col-xs-12 col-sm-offset-3', 'hint' => 'col-sm-6 col-xs-12 col-sm-offset-3']])
	->dropDownList($status, ['prompt' => ''])
	->label($model->getAttributeLabel('breadcrumb')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>