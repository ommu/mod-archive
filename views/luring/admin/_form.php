<?php
/**
 * Archive Lurings (archive-lurings)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 23:20 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="archive-lurings-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
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

<?php echo $form->field($model, 'introduction')
	->textarea(['rows' => 6, 'cols' => 50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('introduction')); ?>

<hr />

<?php $uploadPath = $model::getUploadPath(false);
$senaraiFile = !$model->isNewRecord && $model->old_senarai_file != '' ? '<hr/>'.Yii::t('app', 'Download: {old_senarai_file}', ['old_senarai_file' => Html::a($model->old_senarai_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->old_senarai_file])), ['title'=>$model->old_senarai_file, 'target' => '_blank'])]) : '';
echo $form->field($model, 'senarai_file', ['template' => '{label}{beginWrapper}{input}{error}{hint}<div>'.$senaraiFile.'</div>{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('senarai_file')); ?>

<hr />

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php $submitButtonOption = [];
if (!$model->isNewRecord && Yii::$app->request->isAjax) {
    $submitButtonOption = ArrayHelper::merge($submitButtonOption, [
        'backTo' => Html::a(Html::tag('span', '&laquo;', ['class' => 'mr-1']).Yii::t('app', 'Back to detail'), ['view', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Detail Luring'), 'class' => 'ml-4 modal-btn']),
    ]);
}
echo $form->field($model, 'submitButton')
	->submitButton($submitButtonOption); ?>

<?php ActiveForm::end(); ?>

</div>