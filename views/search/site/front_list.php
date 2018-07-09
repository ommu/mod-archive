<?php
/**
 * ArchiveLists (archive-lists)
 * @var $this SiteController
 * @var $model ArchiveLists
 * @var $dataProvider CActiveDataProvider
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'ArchiveLists',
	);
?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'pager' => array(
		'header' => '',
	), 
	'summaryText' => '',
	'itemsCssClass' => 'items clearfix',
	'pagerCssClass'=>'pager clearfix',
)); ?>
