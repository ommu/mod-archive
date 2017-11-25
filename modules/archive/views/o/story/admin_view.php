<?php
/**
 * Archive Stories (archive-story)
 * @var $this StoryController
 * @var $model ArchiveStory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Stories'=>array('manage'),
		$model->story_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'story_id',
				'value'=>$model->story_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'story_name',
				'value'=>$model->story_name ? $model->story_name : '-',
			),
			array(
				'name'=>'story_desc',
				'value'=>$model->story_desc ? $model->story_desc : '-',
			),
			array(
				'name'=>'story_code',
				'value'=>$model->story_code ? strtoupper($model->story_code) : '-',
			),
			array(
				'name'=>'list_search',
				'value'=>$model->view->lists ? $this->renderPartial('_view_list', array('model'=>$model), true, false) : '-',
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
