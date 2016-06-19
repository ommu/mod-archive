<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'archive-converts-form',
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
		<?php echo $form->labelEx($model,'category_id'); ?>
		<div class="desc">
			<?php 
			$category = ArchiveConvertCategory::getCategory(1);
			if($category != null)
				echo $form->dropDownList($model,'category_id', $category, array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));
			else
				echo $form->dropDownList($model,'category_id', array('prompt'=>Yii::t('phrase', 'Pilih salah satu')));?>
			<?php echo $form->error($model,'category_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<?php if($setting->auto_numbering == 0) {?>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_cat_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'convert_cat_id', array('class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_cat_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	<?php }?>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_title'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'convert_title',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
			<?php echo $form->error($model,'convert_title'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_number'); ?>
		<div class="desc">
			<?php if(!$model->getErrors())
				$model->convert_number = unserialize($model->convert_numbers);
			//echo '<pre>';
			//print_r($model->convert_number);
			//echo '<pre>';?>
			<?php echo $form->textField($model,'convert_number[start]', array('placeholder'=>'Start', 'class'=>'span-3')); ?>
			<?php echo $form->textField($model,'convert_number[finish]', array('placeholder'=>'Finish', 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_number'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_desc'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'convert_desc',array('rows'=>6, 'cols'=>50, 'class'=>'span-10')); ?>
			<?php echo $form->error($model,'convert_desc'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'publish'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'publish'); ?>
			<?php echo $form->labelEx($model,'publish'); ?>
			<?php echo $form->error($model,'publish'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	
	<?php if($model->isNewRecord) {?>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'back_field'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'back_field'); ?>
			<?php echo $form->error($model,'back_field'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	<?php } else {
		$model->back_field = 1;
		echo $form->hiddenField($model,'back_field');
	}?>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


