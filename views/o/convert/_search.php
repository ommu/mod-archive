<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('convert_id'); ?><br/>
			<?php echo $form->textField($model,'convert_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('location_id'); ?><br/>
			<?php echo $form->textField($model,'location_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('category_id'); ?><br/>
			<?php echo $form->textField($model,'category_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_parent'); ?><br/>
			<?php echo $form->textField($model,'convert_parent'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_title'); ?><br/>
			<?php echo $form->textArea($model,'convert_title'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_desc'); ?><br/>
			<?php echo $form->textArea($model,'convert_desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_cat_id'); ?><br/>
			<?php echo $form->textField($model,'convert_cat_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_publish_year'); ?><br/>
			<?php echo $form->textField($model,'convert_publish_year'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_multiple'); ?><br/>
			<?php echo $form->textField($model,'convert_multiple'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_copies'); ?><br/>
			<?php echo $form->textField($model,'convert_copies'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('convert_code'); ?><br/>
			<?php echo $form->textField($model,'convert_code'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_numbers'); ?><br/>
			<?php echo $form->textField($model,'archive_numbers'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_total'); ?><br/>
			<?php echo $form->textArea($model,'archive_total'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_pages'); ?><br/>
			<?php echo $form->textField($model,'archive_pages'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_id'); ?><br/>
			<?php echo $form->textField($model,'creation_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
