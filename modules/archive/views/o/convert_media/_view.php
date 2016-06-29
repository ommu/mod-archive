<?php
/**
 * Archive Convert Medias (archive-convert-media)
 * @var $this ConvertmediaController
 * @var $data ArchiveConvertMedia
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
	<?php echo CHtml::encode($data->publish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_id')); ?>:</b>
	<?php echo CHtml::encode($data->archive_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_id')); ?>:</b>
	<?php echo CHtml::encode($data->convert_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('media_desc')); ?>:</b>
	<?php echo CHtml::encode($data->media_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_id')); ?>:</b>
	<?php echo CHtml::encode($data->creation_id); ?>
	<br />


</div>