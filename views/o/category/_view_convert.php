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
	<li><?php echo $model->getAttributeLabel('convert_search');?>: <?php echo $model->view->converts ? $model->view->converts : 0;?></li>
	<li><?php echo $model->getAttributeLabel('copy_search');?>: <?php echo $model->view->copies ? Yii::t('phrase', '$copies eks', array('$copies'=>$model->view->copies)) : '-';?></li>
	<li><?php echo $model->getAttributeLabel('archive_search');?>: <?php echo $model->view->archives ? $model->view->archives : 0;?></li>
	<li><?php echo $model->getAttributeLabel('archive_page_search');?>: <?php echo $model->view->archive_pages ? $model->view->archive_pages : 0;?></li>
</ul>