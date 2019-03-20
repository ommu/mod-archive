<?php
/**
 * Archive Levels (archive-level)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\LevelController
 * @var $model ommu\archive\models\ArchiveLevel
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 02:27 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use ommu\archive\models\ArchiveLevel;
?>

<div class="archive-level-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'level_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('level_name_i')); ?>

<?php echo $form->field($model, 'level_desc_i')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('level_desc_i')); ?>

<?php $child = ArchiveLevel::getLevel(1);
unset($child[$model->id]);
echo $form->field($model, 'child')
	->checkboxList($child)
	->label($model->getAttributeLabel('child')); ?>

<?php echo $form->field($model, 'sidkkas')
	->checkbox()
	->label($model->getAttributeLabel('sidkkas')); ?>

<?php echo $form->field($model, 'media')
	->checkbox()
	->label($model->getAttributeLabel('media')); ?>

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