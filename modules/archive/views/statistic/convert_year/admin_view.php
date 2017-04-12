<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertyearController
 * @var $model ViewArchiveConvertYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
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
				'name'=>'convert_total_i',
				'value'=>$model->convert_total_i,
			),
			array(
				'name'=>'convert_page_i',
				'value'=>$model->convert_page_i,
			),
			array(
				'name'=>'convert_copy_i',
				'value'=>$model->convert_copy_i,
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>