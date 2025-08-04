<?php
/**
 * Archive Types (archive-type)
 * @var $this TypeController
 * @var $model ArchiveType
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 13 June 2016, 23:55 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Types'=>array('manage'),
		$model->type_id=>array('view','id'=>$model->type_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>