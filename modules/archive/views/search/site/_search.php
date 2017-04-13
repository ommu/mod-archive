<?php
/**
 * Archives (archives)
 * @var $this SiteController
 * @var $model Archives
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('archive_id'); ?><br/>
			<?php echo $form->textField($model,'archive_id',array('size'=>11,'maxlength'=>11)); ?>
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
			<?php echo $model->getAttributeLabel('archive_title'); ?><br/>
			<?php echo $form->textArea($model,'archive_title',array('rows'=>6, 'cols'=>50)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_desc'); ?><br/>
			<?php echo $form->textArea($model,'archive_desc',array('rows'=>6, 'cols'=>50)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_type_id'); ?><br/>
			<?php echo $form->textField($model,'archive_type_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_publish_year'); ?><br/>
			<?php echo $form->textField($model,'archive_publish_year',array('size'=>4,'maxlength'=>4)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_multiple'); ?><br/>
			<?php echo $form->textField($model,'archive_multiple'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_numbers'); ?><br/>
			<?php echo $form->textArea($model,'archive_numbers',array('rows'=>6, 'cols'=>50)); ?>
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
			<?php echo $form->textField($model,'creation_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
