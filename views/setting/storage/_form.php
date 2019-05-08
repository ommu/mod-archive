<?php
/**
 * Archive Storages (archive-storage)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\StorageController
 * @var $model ommu\archive\models\ArchiveStorage
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 17:04 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\archive\models\ArchiveStorage;
?>

<div class="archive-storage-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $parentId = ArchiveStorage::getStorage();
echo $form->field($model, 'parent_id')
	->dropDownList($parentId, ['prompt' => ''])
	->label($model->getAttributeLabel('parent_id')); ?>

<?php echo $form->field($model, 'storage_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('storage_name_i')); ?>

<?php echo $form->field($model, 'storage_desc_i')
	->textarea(['rows'=>4, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('storage_desc_i')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php $button = Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
echo $form->field($model, 'id', ['template' => '{label}{beginWrapper}'.$button.'{endWrapper}'])
	->label(''); ?>

<?php ActiveForm::end(); ?>

</div>