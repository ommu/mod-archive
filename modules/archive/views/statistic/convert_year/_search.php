<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertyearController
 * @var $model ViewArchiveConvertYear
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 23:33 WIB
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
			<?php echo $model->getAttributeLabel('publish_year'); ?><br/>
			<?php echo $form->textField($model,'publish_year',array('size'=>4,'maxlength'=>4)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('converts'); ?><br/>
			<?php echo $form->textField($model,'converts',array('size'=>21,'maxlength'=>21)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
