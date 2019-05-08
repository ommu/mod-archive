<?php
/**
 * Archive Media (archive-media)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\MediaController
 * @var $model ommu\archive\models\ArchiveMedia
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 02:34 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="archive-media-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'media_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('media_name_i')); ?>

<?php echo $form->field($model, 'media_desc_i')
	->textarea(['rows'=>4, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('media_desc_i')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>