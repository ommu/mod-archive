<?php
/**
 * Archives (archives)
 * @var $this SiteController
 * @var $data Archives
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

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->archive_id), array('view', 'id'=>$data->archive_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
	<?php echo CHtml::encode($data->publish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_id')); ?>:</b>
	<?php echo CHtml::encode($data->location_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_id')); ?>:</b>
	<?php echo CHtml::encode($data->type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('story_id')); ?>:</b>
	<?php echo CHtml::encode($data->story_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_title')); ?>:</b>
	<?php echo CHtml::encode($data->archive_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_desc')); ?>:</b>
	<?php echo CHtml::encode($data->archive_desc); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->archive_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_publish_year')); ?>:</b>
	<?php echo CHtml::encode($data->archive_publish_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_multiple')); ?>:</b>
	<?php echo CHtml::encode($data->archive_multiple); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_numbers')); ?>:</b>
	<?php echo CHtml::encode($data->archive_numbers); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_pages')); ?>:</b>
	<?php echo CHtml::encode($data->archive_pages); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_id')); ?>:</b>
	<?php echo CHtml::encode($data->creation_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_date')); ?>:</b>
	<?php echo CHtml::encode($data->modified_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_id')); ?>:</b>
	<?php echo CHtml::encode($data->modified_id); ?>
	<br />

	*/ ?>

</div>