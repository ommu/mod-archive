<?php
/**
 * Archive Types (archive-type)
 * @var $this TypeController
 * @var $model ArchiveType
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:55 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Types'=>array('manage'),
		$model->type_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			/* array(
				'name'=>'type_id',
				'value'=>$model->type_id,
				//'value'=>$model->type_id != '' ? $model->type_id : '-',
			), */
			array(
				'name'=>'type_name',
				'value'=>$model->type_name != '' ? $model->type_name : '-',
			),
			array(
				'name'=>'type_desc',
				'value'=>$model->type_desc != '' ? $model->type_desc : '-',
			),
			array(
				'name'=>'type_code',
				'value'=>$model->type_code != '' ? strtoupper($model->type_code) : '-',
			),
			array(
				'name'=>'archive_search',
				'value'=>$model->view->archives,
			),
			array(
				'name'=>'archive_total_i',
				'value'=>$model->archive_total_i,
			),
			array(
				'name'=>'archive_page_i',
				'value'=>$model->archive_page_i,
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
