<?php
/**
 * Archive Convert Medias (archive-list-convert)
 * @var $this MediaController
 * @var $model ArchiveListConvert
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Convert Medias'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'archive'=>$archive,
	'convert'=>$convert,
)); ?>