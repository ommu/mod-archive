<?php
/**
 * View Archive Years (view-archive-year)
 * @var $this AdminController
 * @var $model ViewArchiveListYear
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 17 June 2016, 06:24 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'View Archive Years'=>array('manage'),
		$model->publish_year,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'publish_year',
				'value'=>!in_array($model->publish_year, array('0000','1970')) ? $model->publish_year : '-',
			),
			array(
				'name'=>'lists',
				'value'=>$model->lists ? $model->lists : 0,
			),
			array(
				'name'=>'copies',
				'value'=>$model->copies ? Yii::t('phrase', '$copies eks', array('$copies'=>$model->copies)) : 0,
			),
			array(
				'name'=>'archives',
				'value'=>$model->archives ? $model->archives : 0,
			),
			array(
				'name'=>'archive_pages',
				'value'=>$model->archive_pages ? $model->archive_pages : 0,
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
