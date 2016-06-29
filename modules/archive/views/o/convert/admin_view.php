<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Converts'=>array('manage'),
		$model->convert_id,
	);
?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		/* array(
			'name'=>'convert_id',
			'value'=>$model->convert_id,
			//'value'=>$model->convert_id != '' ? $model->convert_id : '-',
		), */
		array(
			'name'=>'convert_code',
			'value'=>$model->view->convert_code,
		),
		array(
			'name'=>'location_id',
			'value'=>$model->location_id != 0 ? $model->location->location_name : '-',
		),
		array(
			'name'=>'category_id',
			'value'=>$model->category_id != 0 ? $model->category->category_name : '-',
		),
		array(
			'name'=>'convert_cat_id',
			'value'=>$model->convert_cat_id != 0 ? $model->convert_cat_id : '-',
		),
		array(
			'name'=>'convert_publish_year',
			'value'=>$model->convert_publish_year != '' ? $model->convert_publish_year : '-',
		),
		array(
			'name'=>'convert_title',
			'value'=>$model->convert_title != '' ? $model->convert_title : '-',
		),
		array(
			'name'=>'convert_desc',
			'value'=>$model->convert_desc != '' ? $model->convert_desc : '-',
		),
		array(
			'name'=>'convert_numbers',
			'value'=>ArchiveConverts::getDetailItemArchive(unserialize($model->convert_numbers), $model->convert_multiple),
			'type'=>'raw',
		),
		array(
			'name'=>'convert_total',
			'value'=>$model->convert_total,
		),
		array(
			'name'=>'convert_pages',
			'value'=>$model->convert_pages != 0 ? $model->convert_pages : '-',
		),
		array(
			'name'=>'convert_copies',
			'value'=>$model->convert_copies != 0 ? $model->convert_copies.' eks' : '-',
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