<?php
/**
 * Archive Locations (archive-location)
 * @var $this LocationController
 * @var $model ArchiveLocation
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:53 WIB
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
			<?php echo $model->getAttributeLabel('location_id'); ?><br/>
			<?php echo $form->textField($model,'location_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?><br/>
			<?php echo $form->textField($model,'publish'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('location_name'); ?><br/>
			<?php echo $form->textField($model,'location_name'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('location_desc'); ?><br/>
			<?php echo $form->textArea($model,'location_desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('location_code'); ?><br/>
			<?php echo $form->textField($model,'location_code'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('story_enable'); ?><br/>
			<?php echo $form->textField($model,'story_enable'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('type_enable'); ?><br/>
			<?php echo $form->textField($model,'type_enable'); ?>
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
