<?php
/**
 * View Archive Years (view-archive-year)
 * @var $this AdminController
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
				'value'=>!in_array($model->publish_year, array('0000','1970')) ? $model->publish_year : '-',
			),
			array(
				'name'=>'lists',
				'value'=>$model->lists ? $model->lists : '-',
			),
			array(
				'name'=>'list_copies',
				'value'=>$model->list_copies ? Yii::t('phrase', '$list_copies eks', array('$list_copies'=>$model->list_copies)) : '-',
			),
			array(
				'name'=>'archive_total_i',
				'value'=>$model->archive_total_i ? $model->archive_total_i : '-',
			),
			array(
				'name'=>'archive_pages',
				'value'=>$model->archive_pages ? $model->archive_pages : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
