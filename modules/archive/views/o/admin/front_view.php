<?php
/**
 * Archives (archives)
 * @var $this AdminController
 * @var $model Archives
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archives'=>array('manage'),
		$model->archive_id,
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
			'name'=>'archive_id',
			'value'=>$model->archive_id,
			//'value'=>$model->archive_id != '' ? $model->archive_id : '-',
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
			'name'=>'type_id',
			'value'=>$model->type_id,
			//'value'=>$model->type_id != '' ? $model->type_id : '-',
		),
		array(
			'name'=>'story_id',
			'value'=>$model->story_id,
			//'value'=>$model->story_id != '' ? $model->story_id : '-',
		),
		array(
			'name'=>'archive_title',
			'value'=>$model->archive_title != '' ? $model->archive_title : '-',
			//'value'=>$model->archive_title != '' ? CHtml::link($model->archive_title, Yii::app()->request->baseUrl.'/public/visit/'.$model->archive_title, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'archive_desc',
			'value'=>$model->archive_desc != '' ? $model->archive_desc : '-',
			//'value'=>$model->archive_desc != '' ? CHtml::link($model->archive_desc, Yii::app()->request->baseUrl.'/public/visit/'.$model->archive_desc, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'archive_type_number',
			'value'=>$model->archive_type_number,
			//'value'=>$model->archive_type_number != '' ? $model->archive_type_number : '-',
		),
		array(
			'name'=>'archive_publish_year',
			'value'=>$model->archive_publish_year,
			//'value'=>$model->archive_publish_year != '' ? $model->archive_publish_year : '-',
		),
		array(
			'name'=>'archive_numbers',
			'value'=>$model->archive_numbers != '' ? $model->archive_numbers : '-',
			//'value'=>$model->archive_numbers != '' ? CHtml::link($model->archive_numbers, Yii::app()->request->baseUrl.'/public/visit/'.$model->archive_numbers, array('target' => '_blank')) : '-',
			'type'=>'raw',
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
