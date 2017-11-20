<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $dataProvider CActiveDataProvider
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Converts',
	);
?>

<?php $this->widget('application.libraries.core.components.system.FListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'pager' => array(
		'header' => '',
	), 
	'summaryText' => '',
	'itemsCssClass' => 'items clearfix',
	'pagerCssClass'=>'pager clearfix',
)); ?>
