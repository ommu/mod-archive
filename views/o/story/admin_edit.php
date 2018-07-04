<?php
/**
 * Archive Stories (archive-story)
 * @var $this StoryController
 * @var $model ArchiveStory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Stories'=>array('manage'),
		$model->story_id=>array('view','id'=>$model->story_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>