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
use app\components\ActiveForm;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveLevel;
?>

<div class="archives-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'parent_id')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('parent_id')); ?>

<?php $level = ArchiveLevel::getLevel();
echo $form->field($model, 'level_id')
	->dropDownList($level, ['prompt'=>''])
	->label($model->getAttributeLabel('level_id')); ?>

<?php echo $form->field($model, 'title')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'code')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('code')); ?>

<?php $imageType = Archives::getImageType();
echo $form->field($model, 'image_type')
	->dropDownList($imageType, ['prompt' => ''])
	->label($model->getAttributeLabel('image_type')); ?>

<?php echo $form->field($model, 'sidkkas')
	->checkbox()
	->label($model->getAttributeLabel('sidkkas')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>