<?php
/**
 * Archive Locations (archive-location)
 * @var $this LocationController
 * @var $model ArchiveLocation
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:53 WIB
 * @link https://github.com/ommu/ommu-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Locations'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>