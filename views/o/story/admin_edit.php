<?php
/**
 * Archive Stories (archive-story)
 * @var $this StoryController
 * @var $model ArchiveStory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Stories'=>array('manage'),
		$model->story_id=>array('view','id'=>$model->story_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>