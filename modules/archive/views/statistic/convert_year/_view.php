<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertyearController
 * @var $data ViewArchiveConvertYear
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

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish_year')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->publish_year), array('view', 'id'=>$data->publish_year)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('converts')); ?>:</b>
	<?php echo CHtml::encode($data->converts); ?>
	<br />


</div>