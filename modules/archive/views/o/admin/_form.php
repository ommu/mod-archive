<?php
/**
 * Archives (archives)
 * @var $this AdminController
 * @var $model Archives
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'archives-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<div class="desc">
			<?php 
			$location = ArchiveLocation::getLocation(1);
			if($location != null)
				echo $form->dropDownList($model,'location_id', $location, array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));
			else
				echo $form->dropDownList($model,'location_id', array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));?>
			<?php echo $form->error($model,'location_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'story_id'); ?>
		<div class="desc">
			<?php 
			$story = ArchiveStory::getStory(1);
			if($story != null)
				echo $form->dropDownList($model,'story_id', $story, array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));
			else
				echo $form->dropDownList($model,'story_id', array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));?>
			<?php echo $form->error($model,'story_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'type_id'); ?>
		<div class="desc">
			<?php 
			$type = ArchiveType::getType(1);
			if($type != null)
				echo $form->dropDownList($model,'type_id', $type, array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));
			else
				echo $form->dropDownList($model,'type_id', array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));?>
			<?php echo $form->error($model,'type_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_type_number'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_type_number', array('class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_type_number'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_publish_year'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_publish_year',array('maxlength'=>4, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_publish_year'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_title'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'archive_title',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
			<?php echo $form->error($model,'archive_title'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_desc'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'archive_desc',array('rows'=>6, 'cols'=>50, 'class'=>'span-10')); ?>
			<?php echo $form->error($model,'archive_desc'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_numbers'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'archive_numbers',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'archive_numbers'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'publish'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'publish'); ?>
			<?php echo $form->error($model,'publish'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


