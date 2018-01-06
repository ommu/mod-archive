<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertController
 * @var $model ViewArchiveConvertYear
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 23:33 WIB
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
			<?php echo $model->getAttributeLabel('publish_year'); ?><br/>
			<?php echo $form->textField($model,'publish_year'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('converts'); ?><br/>
			<?php echo $form->textField($model,'converts'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('copies'); ?><br/>
			<?php echo $form->textField($model,'copies'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('archives'); ?><br/>
			<?php echo $form->textField($model,'archives'); ?>
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
