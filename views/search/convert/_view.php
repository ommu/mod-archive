<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $data ArchiveConverts
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->convert_id), array('view', 'id'=>$data->convert_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
	<?php echo CHtml::encode($data->publish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_id')); ?>:</b>
	<?php echo CHtml::encode($data->location_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>:</b>
	<?php echo CHtml::encode($data->category_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_parent')); ?>:</b>
	<?php echo CHtml::encode($data->convert_parent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_title')); ?>:</b>
	<?php echo CHtml::encode($data->convert_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_desc')); ?>:</b>
	<?php echo CHtml::encode($data->convert_desc); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_cat_id')); ?>:</b>
	<?php echo CHtml::encode($data->convert_cat_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_publish_year')); ?>:</b>
	<?php echo CHtml::encode($data->convert_publish_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_multiple')); ?>:</b>
	<?php echo CHtml::encode($data->convert_multiple); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_copies')); ?>:</b>
	<?php echo CHtml::encode($data->convert_copies); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_code')); ?>:</b>
	<?php echo CHtml::encode($data->convert_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_numbers')); ?>:</b>
	<?php echo CHtml::encode($data->archive_numbers); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('archive_total')); ?>:</b>
	<?php echo CHtml::encode($data->archive_total); ?>
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