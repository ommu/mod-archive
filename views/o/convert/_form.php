<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
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
			prev = 'ArchiveConverts\[archive_number_multiple_i\]\['+i+'\]';
			i = i+1;
			next = 'ArchiveConverts\[archive_number_multiple_i\]\['+i+'\]';
			$('form div#show-field').find('[id*="ArchiveConverts_archive_number_multiple_i_"]').each(function() {
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

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
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
		<?php echo $form->labelEx($model,'convert_parent_title_i'); ?>
		<div class="desc">
			<?php if(($model->isNewRecord && $parent == false) || !$model->isNewRecord) {
				if(!$model->getErrors()) {
					if($model->isNewRecord)
						$model->convert_parent = 0;
					if(!$model->isNewRecord)
						$model->convert_parent_title_i = $parent->convert_title;					
				}
				//echo $form->textField($model,'convert_parent_title_i', array('class'=>'span-6'));
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $model,
					'attribute' => 'convert_parent_title_i',
					'source' => Yii::app()->controller->createUrl('suggest'),
					'options' => array(
						//'delay '=> 50,
						'minLength' => 1,
						'showAnim' => 'fold',
						'select' => "js:function(event, ui) {
							$('form #ArchiveConverts_convert_parent_title_i').val(ui.item.value);
							$('form #ArchiveConverts_convert_parent').val(ui.item.id);
							$('form #ArchiveConverts_location_id').val(ui.item.location);
							$('form #ArchiveConverts_category_id').val(ui.item.category);
							$('form #ArchiveConverts_convert_publish_year').val(ui.item.year);
							if(ui.item.multiple == 0)
								$('form #ArchiveConverts_archive_pages').val(ui.item.page);
							else {
								$('form #ArchiveConverts_convert_multiple').prop('checked', true);
								$('div#multiple').slideDown();
								$('div#single').slideUp();
							}
							$('form #ArchiveConverts_convert_copies').val(ui.item.copy);
						}"
					),
					'htmlOptions' => array(
						'class'	=> 'span-6',
					),
				));
				echo $form->hiddenField($model,'convert_parent');
			} else {
				if($model->isNewRecord && !$model->getErrors())
					$model->convert_parent = $parent->convert_id;
				echo $form->hiddenField($model,'convert_parent');
				echo '<strong>'.$parent->convert_title.' ('.$parent->convert_code.')</strong>';
			}?>
			<?php echo $form->error($model,'convert_parent_title_i'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<div class="desc">
			<?php 
			if($parent != false) {
				if($model->isNewRecord && !$model->getErrors())
					$model->location_id = $parent->location_id;
			}
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
			if($parent != false) {
				if($model->isNewRecord && !$model->getErrors())
					$model->category_id = $parent->category_id;				
			}
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
			if($parent != false) {
				if($model->isNewRecord && !$model->getErrors())
					$model->convert_publish_year = $parent->convert_publish_year;
			}
			echo $form->textField($model,'convert_publish_year', array('maxlength'=>4, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'convert_publish_year'); ?>
			<div class="small-px silent mt-5">example: 2015, 2016</div>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_title'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'convert_title', array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
			<?php echo $form->error($model,'convert_title'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'convert_desc'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'convert_desc', array('rows'=>6, 'cols'=>50, 'class'=>'span-10')); ?>
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
		<?php echo $form->labelEx($model,'archive_number_single_i'); ?>
		<div class="desc">
			<?php if($model->convert_multiple == 0 && !$model->getErrors())
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

	<div class="clearfix <?php echo $model->convert_multiple == 1 ? '' :'hide';?>" id="multiple">
		<?php echo $form->labelEx($model,'archive_number_multiple_i'); ?>
		<div class="desc">
			<?php if($model->convert_multiple == 1) {
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

	<div class="clearfix <?php echo $model->convert_multiple == 0 ? '' :'hide';?>" id="single">
		<?php echo $form->labelEx($model,'archive_pages'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'archive_pages', array('maxlength'=>11, 'class'=>'span-3')); ?>
			<?php echo $form->error($model,'archive_pages'); ?>
		</div>
	</div>

	<div class="clearfix <?php echo ($model->convert_parent != 0) ? 'hide' : ''?>">
		<?php echo $form->labelEx($model,'convert_copies'); ?>
		<div class="desc">
			<?php 
			if($parent != false) {
				if($model->isNewRecord && !$model->getErrors())
					$model->convert_copies = $parent->convert_copies;				
			}
			echo $form->textField($model,'convert_copies', array('maxlength'=>11, 'class'=>'span-3')); ?>
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


