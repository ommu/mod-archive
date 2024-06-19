<?php
/**
 * Archive Restoration Histories (archive-restoration-history)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\restoration\HistoryController
 * @var $model ommu\archive\models\search\ArchiveRestorationHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:23 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\archive\models\ArchiveRestorationHistory;
?>

<div class="archive-restoration-history-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'restorationArchiveId');?>

		<?php $condition = $model::getCondition();
			echo $form->field($model, 'condition')
			->dropDownList($condition, ['prompt' => '']);?>

		<?php echo $form->field($model, 'condition_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>