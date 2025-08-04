<?php
/**
 * Archive Settings (archive-settings)
 * @var $this SettingController
 * @var $model ArchiveSettings
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
		'Archive Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		Yii::t('phrase', 'Update'),
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
