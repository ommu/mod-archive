<?php
/**
 * Archives (lists)
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
		'Visits'=>array('manage'),
		'Upload',
	);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'book-grants-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		'on_post' => '',
	),
)); ?>
<div class="dialog-content">
	<fieldset>
		<div class="clearfix">
			<label><?php echo Yii::t('attribute', 'Is Multiple Archive')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo CHtml::checkBox('list_multiple');?>
			</div>
		</div>
		
		<div class="clearfix">
			<label>Excel <span class="required">*</span></label>
			<div class="desc">
				<input type="file" name="importExcel">
				<?php if(Yii::app()->user->hasFlash('errorFile')) {
					echo '<div class="errorMessage">'.Yii::app()->user->getFlash('errorFile').'</div>';
				}?>
				<div class="pt-10"><a off_address="" target="_blank" class="template" href="<?php echo $this->module->assetsUrl;?>/template/import_senarai_template.xlsx" title="Import Template">Import Template</a></div>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton('Import' ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
