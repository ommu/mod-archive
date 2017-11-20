<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this SiteController
 * @var $model ArchiveLists
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'ArchiveLists'=>array('manage'),
		$model->list_id,
	);
?>


<div class="box">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'list_id',
				'value'=>$model->list_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'list_multiple',
				'value'=>$model->list_multiple == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'list_code',
				'value'=>strtoupper($model->list_code),
			),
			array(
				'name'=>'list_title',
				'value'=>$model->list_title ? $model->list_title : '-',
			),
			array(
				'name'=>'list_desc',
				'value'=>$model->list_desc != '' ? $model->list_desc : '-',
			),
			array(
				'name'=>'location_id',
				'value'=>$model->location_id ? $model->location->location_name : '-',
			),
			array(
				'name'=>'story_id',
				'value'=>$model->story_id ? $model->story->story_name : '-',
			),
			array(
				'name'=>'type_id',
				'value'=>$model->type_id ? $model->type->type_name : '-',
			),
			array(
				'name'=>'list_type_id',
				'value'=>$model->list_type_id ? $model->list_type_id : '-',
			),
			array(
				'name'=>'list_publish_year',
				'value'=>!in_array($model->list_publish_year, array('0000','1970')) ? $model->list_publish_year : '-',
			),
			array(
				'name'=>'list_copies',
				'value'=>$model->list_copies ? Yii::t('phrase', '$list_copies eks', array('$list_copies'=>$model->list_copies)) : '-',
			),
			array(
				'name'=>'archive_numbers',
				'value'=>ArchiveLists::getDetailItemArchive(unserialize($model->archive_numbers), $model->list_multiple),
				'type'=>'raw',
			),
			array(
				'name'=>'archive_total',
				'value'=>$model->archive_total ? $model->archive_total : 0,
			),
			array(
				'name'=>'archive_pages',
				'value'=>$model->archive_pages ? $model->archive_pages : 0,
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation_id ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id ? $model->modified->displayname : '-',
			),
		),
	)); ?>
</div>