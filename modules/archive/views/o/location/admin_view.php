<?php
/**
 * Archive Locations (archive-location)
 * @var $this LocationController
 * @var $model ArchiveLocation
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:53 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
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
			array(
				'name'=>'location_id',
				'value'=>$model->location_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'location_name',
				'value'=>$model->location_name ? $model->location_name : '-',
			),
			array(
				'name'=>'location_desc',
				'value'=>$model->location_desc ? $model->location_desc : '-',
			),
			array(
				'name'=>'location_code',
				'value'=>$model->location_code ? strtoupper($model->location_code) : '-',
			),
			array(
				'name'=>'story_enable',
				'value'=>$model->story_enable == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
			),
			array(
				'name'=>'type_enable',
				'value'=>$model->type_enable == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
			),
			array(
				'name'=>'list_search',
				'value'=>$model->view->lists ? $this->renderPartial('_view_list', array('model'=>$model), true, false) : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'convert_search',
				'value'=>$model->view->converts ? $this->renderPartial('_view_convert', array('model'=>$model), true, false) : '-',
				'type'=>'raw',
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
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
