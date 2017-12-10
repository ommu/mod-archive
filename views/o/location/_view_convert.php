<?php
/**
 * Archive Convert Categories (archive-convert-category)
 * @var $this CategoryController
 * @var $model ArchiveConvertCategory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 06:48 WIB
 * @link https://github.com/ommu/ommu-archive
 * @contact (+62)856-299-4114
 *
 */
?>

<ul>
	<li><?php echo $model->getAttributeLabel('convert_search');?>: <?php echo $model->view->converts ? $model->view->converts : 0;?></li>
	<li><?php echo $model->getAttributeLabel('convert_copy_search');?>: <?php echo $model->view->convert_copies ? Yii::t('phrase', '$convert_copies eks', array('$convert_copies'=>$model->view->convert_copies)) : 0;?></li>
	<li><?php echo $model->getAttributeLabel('convert_archive_search');?>: <?php echo $model->view->convert_archives ? $model->view->convert_archives : 0;?></li>
	<li><?php echo $model->getAttributeLabel('convert_archive_page_search');?>: <?php echo $model->view->convert_archive_pages ? $model->view->convert_archive_pages : 0;?></li>
</ul>