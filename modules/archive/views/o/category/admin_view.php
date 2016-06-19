<?php
/**
 * Archive Convert Categories (archive-convert-category)
 * @var $this CategoryController
 * @var $model ArchiveConvertCategory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 17 June 2016, 06:48 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Convert Categories'=>array('manage'),
		$model->category_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			/* array(
				'name'=>'category_id',
				'value'=>$model->category_id,
				//'value'=>$model->category_id != '' ? $model->category_id : '-',
			), */
			array(
				'name'=>'category_name',
				'value'=>$model->category_name != '' ? $model->category_name : '-',
			),
			array(
				'name'=>'category_desc',
				'value'=>$model->category_desc != '' ? $model->category_desc : '-',
			),
			array(
				'name'=>'category_code',
				'value'=>$model->category_code != '' ? strtoupper($model->category_code) : '-',
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
