<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this AdminController
 * @var $model ArchiveLists
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
		'ArchiveLists'=>array('manage'),
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
	)); ?>
</div>
