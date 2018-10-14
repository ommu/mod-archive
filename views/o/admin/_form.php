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

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#ArchiveLists_list_multiple').on('change', function() {
		var id = $(this).prop('checked');		
		if(id == true) {
			$('div#multiple').slideDown();
			$('div#single').slideUp();
		} else {
			$('div#single').slideDown();
			$('div#multiple').slideUp();
		}
	});
	var i = 0;
	var prev, next;
	$('input[type="button"]#add-field').on('click', function() {
		var body = $('form div#show-field').html();
		if($('#add-field').before(body)) {
			prev = 'ArchiveLists\[archive_number_multiple_i\]\['+i+'\]';
			i = i+1;
			next = 'ArchiveLists\[archive_number_multiple_i\]\['+i+'\]';
			$('form div#show-field').find('[id*="ArchiveLists_archive_number_multiple_i_"]').each(function() {
				$(this).attr('name',$(this).attr('name').replace(prev,next));
			});
			//alert(prev+' '+next);
		}
	});
	$('a.drop').on('click', function() {
		$(this).parents('div.field').remove();
	});
EOP;
	$cs->registerScript('archive', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'archive-lists-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		//'enctype' => 'multipart/form-data',
		'class'=>'hide',
	),
)); ?>
<div id="show-field">
	<?php echo $this->renderPartial('_form_field', array(
		'form'=>$form,
		'model'=>$model,
	)); ?>
</div>
<?php $this->endWidget(); ?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'archive-lists-form',
	'enableAjaxValidation'=>true,
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

	<?php if($setting->auto_numbering == 0) {?>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'list_type_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'list_type_id', array('class'=>'span-3')); ?>
			<?php echo $form->error($model,'list_type_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	<?php }?>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'list_publish_year'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'list_publish_year', array('maxlength'=>4, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'list_publish_year'); ?>
			<div class="small-px silent mt-5">example: 2015, 2016</div>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'list_title'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'list_title', array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
			<?php echo $form->error($model,'list_title'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'list_desc'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'list_desc', array('rows'=>6, 'cols'=>50, 'class'=>'span-10')); ?>
			<?php echo $form->error($model,'list_desc'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'list_multiple'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'list_multiple'); ?>
			<?php echo $form->error($model,'list_multiple'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->list_multiple == 0 ? '' :'hide';?>" id="single">
		<?php echo $form->labelEx($model,'archive_number_single_i'); ?>
		<div class="desc">
			<?php if(!$model->isNewRecord && $model->list_multiple == 0)
				$model->archive_number_single_i = unserialize($model->archive_numbers);
			//echo '<pre>';
			//print_r($model->archive_number_single_i);
			//echo '<pre>';?>
			<?php echo $form->textField($model,'archive_number_single_i[start]', array('placeholder'=>'Start', 'class'=>'span-3')); ?>
			<?php echo $form->textField($model,'archive_number_single_i[finish]', array('placeholder'=>'Finish', 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_number_single_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->list_multiple == 1 ? '' :'hide';?>" id="multiple">
		<?php echo $form->labelEx($model,'archive_number_multiple_i'); ?>
		<div class="desc">
			<?php if($model->list_multiple == 1) {
				if(!$model->getErrors())
					$data = $model->archive_number_multiple_i = unserialize($model->archive_numbers);
				else
					$data = $model->archive_number_multiple_i;
			}
			if(!empty($data)) {
				foreach($data as $key => $val) {
					echo $this->renderPartial('_form_field', array(
						'form'=>$form,
						'model'=>$model,
						'key'=>$key,
					));
				}
			}
			//echo '<pre>';
			//print_r($model->archive_number_multiple_i);
			//echo '<pre>';?>
			<?php echo CHtml::button(Yii::t('phrase', 'Add Field'), array('id'=>'add-field')); ?>
			<?php echo $form->error($model,'archive_number_multiple_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->list_multiple == 0 ? '' :'hide';?>" id="single">
		<?php echo $form->labelEx($model,'archive_pages'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_pages', array('maxlength'=>11, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_pages'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'list_copies'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'list_copies', array('maxlength'=>11, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'list_copies'); ?>
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
		<?php echo $form->labelEx($model,'back_field_i'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'back_field_i'); ?>
			<?php echo $form->error($model,'back_field_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	<?php } else {
		$model->back_field_i = 1;
		echo $form->hiddenField($model,'back_field_i');
	}?>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


