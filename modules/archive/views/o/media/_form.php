<?php
/**
 * Archive Convert Medias (archive-list-convert)
 * @var $this MediaController
 * @var $model ArchiveListConvert
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'archive-list-convert-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'list_code_i'); ?>
			<div class="desc">
				<?php //echo $form->textField($model,'list_code_i',array('maxlength'=>32));
				if(!$model->getErrors())
					$model->list_code_i = strtoupper($archive->list_code);
				if($model->isNewRecord) {
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $model,
						'attribute' => 'list_code_i',
						'source' => Yii::app()->controller->createUrl('o/admin/suggest'),
						'options' => array(
							//'delay '=> 50,
							'minLength' => 1,
							'showAnim' => 'fold',
							'select' => "js:function(event, ui) {
								$('form #ArchiveListConvert_list_code_i').val(ui.item.value);
								$('form #ArchiveListConvert_list_id').val(ui.item.id);
							}"
						),
						'htmlOptions' => array(
							'class'	=> 'span-7',
							'maxlength'=>32,
						),
					));
					$model->list_id = $archive->list_id;
				} else
					echo $form->textField($model,'list_code_i',array('maxlength'=>32, 'class'=>'span-7', 'disabled'=>'disabled'));
				
				echo $form->hiddenField($model,'list_id'); ?>
				<?php echo $form->error($model,'list_code_i'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'convert_code_i'); ?>
			<div class="desc">
				<?php //echo $form->textField($model,'convert_code_i',array('maxlength'=>32));
				if(!$model->getErrors())
					$model->convert_code_i = strtoupper($convert->convert_code);
				if($model->isNewRecord) {
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $model,
						'attribute' => 'convert_code_i',
						'source' => Yii::app()->controller->createUrl('o/convert/suggest'),
						'options' => array(
							//'delay '=> 50,
							'minLength' => 1,
							'showAnim' => 'fold',
							'select' => "js:function(event, ui) {
								$('form #ArchiveListConvert_convert_code_i').val(ui.item.value);
								$('form #ArchiveListConvert_convert_id').val(ui.item.id);
							}"
						),
						'htmlOptions' => array(
							'class'	=> 'span-7',
							'maxlength'=>32,
						),
					));
					$model->convert_id = $convert->convert_id;
				} else
					echo $form->textField($model,'convert_code_i',array('maxlength'=>32, 'class'=>'span-7', 'disabled'=>'disabled'));
				
				echo $form->hiddenField($model,'convert_id'); ?>
				<?php echo $form->error($model,'convert_code_i'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'media_desc'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'media_desc',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'media_desc'); ?>
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

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


