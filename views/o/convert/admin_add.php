<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Converts'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php
if(Yii::app()->user->hasFlash('error'))
	echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
if(Yii::app()->user->hasFlash('success'))
	echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
?>
</div>
<?php //begin.Messages ?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
		'parent'=>$parent,
	)); ?>
</div>
