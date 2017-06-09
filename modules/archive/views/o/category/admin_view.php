<?php
/**
 * Archive Convert Categories (archive-convert-category)
 * @var $this CategoryController
 * @var $model ArchiveConvertCategory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 06:48 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
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
				'value'=>$model->view->converts ? $model->view->converts : 0,
			),
			array(
				'name'=>'convert_total_i',
				'value'=>$model->convert_total_i,
			),
			array(
				'name'=>'convert_page_i',
				'value'=>$model->convert_page_i,
			),
			array(
				'name'=>'convert_copy_i',
				'value'=>$model->convert_copy_i,
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
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
