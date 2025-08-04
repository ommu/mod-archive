<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this AdminController
 * @var $model ArchiveLists
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 13 June 2016, 23:54 WIB
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
			<?php echo $model->getAttributeLabel('list_id'); ?><br/>
			<?php echo $form->textField($model,'list_id'); ?>
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
			<?php echo $model->getAttributeLabel('type_id'); ?><br/>
			<?php echo $form->textField($model,'type_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('story_id'); ?><br/>
			<?php echo $form->textField($model,'story_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_title'); ?><br/>
			<?php echo $form->textArea($model,'list_title'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_desc'); ?><br/>
			<?php echo $form->textArea($model,'list_desc'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_type_id'); ?><br/>
			<?php echo $form->textField($model,'list_type_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_publish_year'); ?><br/>
			<?php echo $form->textField($model,'list_publish_year'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_multiple'); ?><br/>
			<?php echo $form->textArea($model,'list_multiple'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_copies'); ?><br/>
			<?php echo $form->textArea($model,'list_copies'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_code'); ?><br/>
			<?php echo $form->textArea($model,'list_code'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_numbers'); ?><br/>
			<?php echo $form->textArea($model,'archive_numbers'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_total'); ?><br/>
			<?php echo $form->textArea($model,'archive_total'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_pages'); ?><br/>
			<?php echo $form->textArea($model,'archive_pages'); ?>
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
