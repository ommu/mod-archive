<?php
/**
 * Archive Convert Categories (archive-convert-category)
 * @var $this CategoryController
 * @var $model ArchiveConvertCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 17 June 2016, 06:48 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */
?>

<ul>
	<li><?php echo $model->getAttributeLabel('list_search');?>: <?php echo $model->view->lists ? $model->view->lists : 0;?></li>
	<li><?php echo $model->getAttributeLabel('list_copy_search');?>: <?php echo $model->view->list_copies ? Yii::t('phrase', '$list_copies eks', array('$list_copies'=>$model->view->list_copies)) : 0;?></li>
	<li><?php echo $model->getAttributeLabel('list_archive_search');?>: <?php echo $model->view->list_archives ? $model->view->list_archives : 0;?></li>
	<li><?php echo $model->getAttributeLabel('list_archive_page_search');?>: <?php echo $model->view->list_archive_pages ? $model->view->list_archive_pages : 0;?></li>
</ul>