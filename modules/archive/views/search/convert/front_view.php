<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
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
		'Archive Converts'=>array('manage'),
		$model->convert_id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'convert_id',
			'value'=>$model->convert_id,
			//'value'=>$model->convert_id != '' ? $model->convert_id : '-',
		),
		array(
			'name'=>'publish',
			'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			//'value'=>$model->publish,
		),
		array(
			'name'=>'location_id',
			'value'=>$model->location_id,
			//'value'=>$model->location_id != '' ? $model->location_id : '-',
		),
		array(
			'name'=>'category_id',
			'value'=>$model->category_id,
			//'value'=>$model->category_id != '' ? $model->category_id : '-',
		),
		array(
			'name'=>'convert_parent',
			'value'=>$model->convert_parent,
			//'value'=>$model->convert_parent != '' ? $model->convert_parent : '-',
		),
		array(
			'name'=>'convert_title',
			'value'=>$model->convert_title != '' ? $model->convert_title : '-',
			//'value'=>$model->convert_title != '' ? CHtml::link($model->convert_title, Yii::app()->request->baseUrl.'/public/visit/'.$model->convert_title, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'convert_desc',
			'value'=>$model->convert_desc != '' ? $model->convert_desc : '-',
			//'value'=>$model->convert_desc != '' ? CHtml::link($model->convert_desc, Yii::app()->request->baseUrl.'/public/visit/'.$model->convert_desc, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'convert_cat_id',
			'value'=>$model->convert_cat_id,
			//'value'=>$model->convert_cat_id != '' ? $model->convert_cat_id : '-',
		),
		array(
			'name'=>'convert_publish_year',
			'value'=>$model->convert_publish_year,
			//'value'=>$model->convert_publish_year != '' ? $model->convert_publish_year : '-',
		),
		array(
			'name'=>'convert_multiple',
			'value'=>$model->convert_multiple,
			//'value'=>$model->convert_multiple != '' ? $model->convert_multiple : '-',
		),
		array(
			'name'=>'archive_numbers',
			'value'=>$model->archive_numbers != '' ? $model->archive_numbers : '-',
			//'value'=>$model->archive_numbers != '' ? CHtml::link($model->archive_numbers, Yii::app()->request->baseUrl.'/public/visit/'.$model->archive_numbers, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'archive_pages',
			'value'=>$model->archive_pages,
			//'value'=>$model->archive_pages != '' ? $model->archive_pages : '-',
		),
		array(
			'name'=>'convert_copies',
			'value'=>$model->convert_copies,
			//'value'=>$model->convert_copies != '' ? $model->convert_copies : '-',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation_id,
			//'value'=>$model->creation_id != 0 ? $model->creation_id : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id,
			//'value'=>$model->modified_id != 0 ? $model->modified_id : '-',
		),
	),
)); ?>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
