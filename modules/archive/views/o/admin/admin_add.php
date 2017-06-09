<?php
/**
 * Archives (archives)
 * @var $this AdminController
 * @var $model Archives
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Archives'=>array('manage'),
		'Create',
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php
if(Yii::app()->user->hasFlash('error'))
	echo Utility::flashError(Yii::app()->user->getFlash('error'));
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
</div>
<?php //begin.Messages ?>
	
<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
	)); ?>
</div>
