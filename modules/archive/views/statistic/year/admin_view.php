<?php
/**
 * View Archive Years (view-archive-year)
 * @var $this YearController
 * @var $model ViewArchiveYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 17 June 2016, 06:24 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'View Archive Years'=>array('manage'),
		$model->publish_year,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'publish_year',
			'value'=>$model->publish_year,
			//'value'=>$model->publish_year != '' ? $model->publish_year : '-',
		),
		array(
			'name'=>'archives',
			'value'=>$model->archives,
			//'value'=>$model->archives != '' ? $model->archives : '-',
		),
	),
)); ?>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
