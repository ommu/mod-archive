<?php
/**
 * View Archive Years (view-archive-year)
 * @var $this YearController
 * @var $model ViewArchiveYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 06:24 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'View Archive Years'=>array('manage'),
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
				'name'=>'archives',
				'value'=>$model->archives,
			),
			array(
				'name'=>'archive_total_i',
				'value'=>$model->archive_total_i,
			),
			array(
				'name'=>'archive_page_i',
				'value'=>$model->archive_page_i,
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
