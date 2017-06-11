<?php
/**
 * View Archive Years (view-archive-year)
 * @var $this AdminController
 * @var $model ViewArchiveListYear
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 06:24 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('publish_year'); ?><br/>
			<?php echo $form->textField($model,'publish_year'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lists'); ?><br/>
			<?php echo $form->textField($model,'lists'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('list_copies'); ?><br/>
			<?php echo $form->textField($model,'list_copies'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archive_pages'); ?><br/>
			<?php echo $form->textField($model,'archive_pages'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
