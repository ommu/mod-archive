<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Converts'=>array('manage'),
		$model->convert_id,
	);
?>

<div class="box">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'convert_id',
				'value'=>$model->convert_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'convert_multiple',
				'value'=>$model->convert_multiple == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'convert_code',
				'value'=>$model->convert_code,
			),
			array(
				'name'=>'convert_title',
				'value'=>$model->convert_title ? $model->convert_title : '-',
			),
			array(
				'name'=>'convert_desc',
				'value'=>$model->convert_desc ? $model->convert_desc : '-',
			),
			array(
				'name'=>'convert_parent',
				'value'=>$model->convert_parent != 0 ? $model->convert_parent : '-',
			),
			array(
				'name'=>'location_id',
				'value'=>$model->location_id ? $model->location->location_name : '-',
			),
			array(
				'name'=>'category_id',
				'value'=>$model->category_id ? $model->category->category_name : '-',
			),
			array(
				'name'=>'convert_cat_id',
				'value'=>$model->convert_cat_id ? $model->convert_cat_id : '-',
			),
			array(
				'name'=>'convert_publish_year',
				'value'=>!in_array($model->convert_publish_year, array('0000','1970')) ? $model->convert_publish_year : '-',
			),
			array(
				'name'=>'convert_copies',
				'value'=>$model->convert_copies ? Yii::t('phrase', '$convert_copies eks', array('$convert_copies'=>$model->convert_copies)) : '-',
			),
			array(
				'name'=>'archive_numbers',
				'value'=>ArchiveConverts::getDetailItemArchive(unserialize($model->archive_numbers), $model->convert_multiple),
				'type'=>'raw',
			),
			array(
				'name'=>'archive_total',
				'value'=>$model->archive_total ? $model->archive_total : '-',
			),
			array(
				'name'=>'archive_pages',
				'value'=>$model->archive_pages ? $model->archive_pages : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id ? $model->modified->displayname : '-',
			),
		),
	)); ?>
</div>