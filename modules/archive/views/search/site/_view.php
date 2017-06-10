<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this SiteController
 * @var $data ArchiveLists
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->list_id), array('view', 'id'=>$data->list_id)); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_title')); ?>:</b>
	<?php echo CHtml::encode($data->list_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_desc')); ?>:</b>
	<?php echo CHtml::encode($data->list_desc); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('list_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->list_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_publish_year')); ?>:</b>
	<?php echo CHtml::encode($data->list_publish_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('list_multiple')); ?>:</b>
	<?php echo CHtml::encode($data->list_multiple); ?>
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