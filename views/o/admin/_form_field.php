<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this AdminController
 * @var $model ArchiveLists
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$key = $model->isNewRecord ? '0' : (empty($model->archive_number_multiple_i) ? '0' : $key);
?>

<div class="field">
	<?php echo $form->textField($model,"archive_number_multiple_i[$key][id]", array('placeholder'=>'Detail Archive', 'class'=>'span-4')); ?>
	<?php echo $form->textField($model,"archive_number_multiple_i[$key][start]", array('placeholder'=>'Start', 'class'=>'span-2')); ?>
	<?php echo $form->textField($model,"archive_number_multiple_i[$key][finish]", array('placeholder'=>'Finish', 'class'=>'span-2')); ?>
	<?php echo $form->textField($model,"archive_number_multiple_i[$key][pages]", array('placeholder'=>'Pages', 'class'=>'span-2')); ?>
	<a class="drop" href="javascript:void(0);" title="<?php echo Yii::t('phrase', 'Drop');?>"><?php echo Yii::t('phrase', 'Drop');?></a>
</div>