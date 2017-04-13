<?php
/**
 * Archive Convert Medias (archive-convert-media)
 * @var $this ConvertmediaController
 * @var $model ArchiveConvertMedia
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archive Convert Medias'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<?php echo $this->renderPartial('/o/convert_media/_form', array(
	'model'=>$model,
	'archive'=>$archive,
	'convert'=>$convert,
)); ?>