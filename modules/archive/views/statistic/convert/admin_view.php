<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertController
 * @var $model ViewArchiveConvertYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 23:33 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'View Archive Convert Years'=>array('manage'),
		$model->publish_year,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'publish_year',
				'value'=>!in_array($model->publish_year, array('0000','1970')) ? $model->publish_year : '-',
			),
			array(
				'name'=>'converts',
				'value'=>$model->converts ? $model->converts : 0,
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