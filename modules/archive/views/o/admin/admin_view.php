<?php
/**
 * Archives (archives)
 * @var $this AdminController
 * @var $model Archives
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archives'=>array('manage'),
		$model->archive_id,
	);
?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		/* array(
			'name'=>'archive_id',
			'value'=>$model->archive_id,
			//'value'=>$model->archive_id != '' ? $model->archive_id : '-',
		), */
		array(
			'name'=>'archive_code',
			'value'=>$model->view->archive_code,
		),
		array(
			'name'=>'location_id',
			'value'=>$model->location_id != 0 ? $model->location->location_name : '-',
		),
		array(
			'name'=>'story_id',
			'value'=>$model->story_id != 0 ? $model->story->story_name : '-',
		),
		array(
			'name'=>'type_id',
			'value'=>$model->type_id != 0 ? $model->type->type_name : '-',
		),
		array(
			'name'=>'archive_type_id',
			'value'=>$model->archive_type_id != 0 ? $model->archive_type_id : '-',
		),
		array(
			'name'=>'archive_publish_year',
			'value'=>$model->archive_publish_year != '' ? $model->archive_publish_year : '-',
		),
		array(
			'name'=>'archive_title',
			'value'=>$model->archive_title != '' ? $model->archive_title : '-',
		),
		array(
			'name'=>'archive_desc',
			'value'=>$model->archive_desc != '' ? $model->archive_desc : '-',
		),
		array(
			'name'=>'archive_numbers',
			'value'=>Archives::getDetailItemArchive(unserialize($model->archive_numbers), $model->archive_multiple),
			'type'=>'raw',
		),
		array(
			'name'=>'archive_total_i',
			'value'=>$model->archive_total_i,
		),
		array(
			'name'=>'archive_pages',
			'value'=>$model->archive_pages != 0 ? $model->archive_pages : '-',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation_id != 0 ? $model->creation->displayname : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id != 0 ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'publish',
			'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
	),
)); ?>