<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#ArchiveConverts_convert_multiple').on('change', function() {
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
			prev = 'ArchiveConverts\[convert_number_multiple\]\['+i+'\]';
			i = i+1;
			next = 'ArchiveConverts\[convert_number_multiple\]\['+i+'\]';
			$('form div#show-field').find('[id*="ArchiveConverts_convert_number_multiple_"]').each(function() {
				$(this).attr('name',$(this).attr('name').replace(prev,next));
			});
			//alert(prev+' '+next);
		}
	});
	$('a.drop').live('click', function() {
		$(this).parents('div.field').remove();
	});
EOP;
	$cs->registerScript('archive', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'archive-converts-form',
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
		<?php echo $form->labelEx($model,'convert_parent'); ?>
		<div class="desc">
			<?php if($parent == false) {
				echo $form->textField($model,'convert_parent', array('class'=>'span-6'));
			} else {
				$model->convert_parent = $parent->convert_id;
				echo $form->hiddenField($model,'convert_parent');
				echo '<strong>'.$parent->convert_title.' ('.$parent->view->convert_code.')</strong>';
			}?>
			<?php echo $form->error($model,'convert_parent'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<div class="desc">
			<?php 
			if($parent != false)
				$model->location_id = $parent->location_id;
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
			if($parent != false)
				$model->category_id = $parent->category_id;
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
		<?php echo $form->labelEx($model,'convert_publish_year'); ?>
		<div class="desc">
			<?php 
			if($parent != false)
				$model->convert_publish_year = $parent->convert_publish_year;
			echo $form->textField($model,'convert_publish_year',array('maxlength'=>4, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_publish_year'); ?>
			<div class="small-px silent mt-5">example: 2015, 2016</div>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_title'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'convert_title',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
			<?php echo $form->error($model,'convert_title'); ?>
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
		<?php echo $form->labelEx($model,'convert_multiple'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'convert_multiple'); ?>
			<?php echo $form->error($model,'convert_multiple'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->convert_multiple == 0 ? '' :'hide';?>" id="single">
		<?php echo $form->labelEx($model,'convert_number_single'); ?>
		<div class="desc">
			<?php if($model->convert_multiple == 0 && !$model->getErrors())
				$model->convert_number_single = unserialize($model->convert_numbers);
			//echo '<pre>';
			//print_r($model->convert_number_single);
			//echo '<pre>';?>
			<?php echo $form->textField($model,'convert_number_single[start]', array('placeholder'=>'Start', 'class'=>'span-3')); ?>
			<?php echo $form->textField($model,'convert_number_single[finish]', array('placeholder'=>'Finish', 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_number_single'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix <?php echo $model->convert_multiple == 1 ? '' :'hide';?>" id="multiple">
		<?php echo $form->labelEx($model,'convert_number_multiple'); ?>
		<div class="desc">
			<?php if($model->convert_multiple == 1) {
				if(!$model->getErrors())
					$data = $model->convert_number_multiple = unserialize($model->convert_numbers);
				else
					$data = $model->convert_number_multiple;
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
			//print_r($model->convert_number_multiple);
			//echo '<pre>';?>
			<?php echo CHtml::button(Yii::t('phrase', 'Add Field'), array('id'=>'add-field')); ?>
			<?php echo $form->error($model,'convert_number_multiple'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_pages'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'convert_pages',array('maxlength'=>11, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_pages'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_copies'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'convert_copies',array('maxlength'=>11, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_copies'); ?>
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


