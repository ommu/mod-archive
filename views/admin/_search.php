<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\search\Archives
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveLevel;
use ommu\archive\models\ArchiveSetting;
?>

<div class="archives-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'parent_id');?>

		<?php $level = ArchiveLevel::getLevel();
		echo $form->field($model, 'level_id')
			->dropDownList($level, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'title');?>

		<?php echo $form->field($model, 'code');?>

		<?php $imageType = Archives::getImageType();
			echo $form->field($model, 'image_type')
			->dropDownList($imageType, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php if(ArchiveSetting::getInfo('fond_sidkkas')) {
			echo $form->field($model, 'sidkkas')
				->dropDownList($model->filterYesNo(), ['prompt'=>'']);
		}?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>