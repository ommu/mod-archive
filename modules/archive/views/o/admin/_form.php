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

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#Archives_archive_multiple').on('change', function() {
		var id = $(this).prop('checked');		
		if(id == true) {
			$('div#multiple').slideDown();
			$('div#single').slideUp();
		} else {
			$('div#single').slideDown();
			$('div#multiple').slideUp();
		}
	});
	$('input[type="button"]#add-field').on('click', function() {
		var body = $(this).parents('form').find('div#show-field').html();
		$('#add-field').before(body);
	});
	$('a.drop').live('click', function() {
		$(this).parents('div.field').remove();
	});
EOP;
	$cs->registerScript('archive', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'archives-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div id="show-field" class="hide">
	<?php echo $this->renderPartial('_form_field', array(
		'model'=>$model,
		'form'=>$form,
	)); ?>
</div>

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
		<?php echo $form->labelEx($model,'archive_type_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_type_id', array('class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_type_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'archive_publish_year'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_publish_year',array('maxlength'=>4, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_publish_year'); ?>
			<div class="small-px silent mt-5">example: 2015, 2016</div>
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

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'archive_multiple'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'archive_multiple'); ?>
			<?php echo $form->error($model,'archive_multiple'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->archive_multiple == 0 ? '' :'hide';?>" id="single">
		<?php echo $form->labelEx($model,'archive_number_single'); ?>
		<div class="desc">
			<?php if(!$model->isNewRecord && $model->archive_multiple == 0)
				$model->archive_number_single = unserialize($model->archive_numbers);
			//print_r($model->archive_number_single);?>
			<?php echo $form->textField($model,'archive_number_single[start]', array('placeholder'=>'Start', 'class'=>'span-3')); ?>
			<?php echo $form->textField($model,'archive_number_single[finish]', array('placeholder'=>'Finish', 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_number_single'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->archive_multiple == 1 ? '' :'hide';?>" id="multiple">
		<?php echo $form->labelEx($model,'archive_number_multiple'); ?>
		<div class="desc">
			<?php if(!$model->isNewRecord) {
				if($model->archive_multiple == 1)
					$model->archive_number_multiple = unserialize($model->archive_numbers);
				$data = count($model->archive_number_multiple)/3;
				for($i = 0; $i<$data; $i++) {
					echo $this->renderPartial('_form_field', array(
						'model'=>$model,
						'form'=>$form,
					));
				}
			}				
			print_r($model->archive_number_multiple);?>
			<?php echo CHtml::button(Yii::t('phrase', 'Add Field'), array('id'=>'add-field')); ?>
			<?php echo $form->error($model,'archive_number_multiple'); ?>
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


