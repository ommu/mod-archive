<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertyearController
 * @var $model ViewArchiveConvertYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 23:33 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'View Archive Convert Years'=>array('manage'),
		$model->publish_year,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'publish_year',
				'value'=>$model->publish_year != '' ? $model->publish_year : '-',
			),
			array(
				'name'=>'converts',
				'value'=>$model->converts,
			),
			array(
				'name'=>'convert_total',
				'value'=>$model->convert_total,
			),
			array(
				'name'=>'convert_pages',
				'value'=>$model->convert_pages,
			),
			array(
				'name'=>'convert_copies',
				'value'=>$model->convert_copies,
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>