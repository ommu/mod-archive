<?php
/**
 * Archive Locations (archive-location)
 * @var $this LocationController
 * @var $model ArchiveLocation
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:53 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Locations'=>array('manage'),
		$model->location_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			/* array(
				'name'=>'location_id',
				'value'=>$model->location_id,
				//'value'=>$model->location_id != '' ? $model->location_id : '-',
			), */
			array(
				'name'=>'location_name',
				'value'=>$model->location_name != '' ? $model->location_name : '-',
			),
			array(
				'name'=>'location_desc',
				'value'=>$model->location_desc != '' ? $model->location_desc : '-',
			),
			array(
				'name'=>'location_code',
				'value'=>$model->location_code != '' ? strtoupper($model->location_code) : '-',
			),
			array(
				'name'=>'story_enable',
				'value'=>$model->story_enable == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
			),
			array(
				'name'=>'archive_search',
				'value'=>$model->view->archives,
			),
			array(
				'name'=>'archive_total',
				'value'=>$model->archive_total,
			),
			array(
				'name'=>'archive_pages',
				'value'=>$model->archive_pages,
			),
			array(
				'name'=>'convert_search',
				'value'=>$model->view->converts,
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
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id != 0 ? $model->creation_relation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id != 0 ? $model->modified_relation->displayname : '-',
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
