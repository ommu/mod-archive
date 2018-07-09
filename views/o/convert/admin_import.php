<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Visits'=>array('manage'),
		'Upload',
	);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
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
			<label><?php echo Yii::t('attribute', 'Is Multiple Convert')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo CHtml::checkBox('convert_multiple');?>
			</div>
		</div>
		
		<div class="clearfix">
			<label>Excel <span class="required">*</span></label>
			<div class="desc">
				<input type="file" name="importExcel">
				<?php if(Yii::app()->user->hasFlash('errorFile')) {
					echo '<div class="errorMessage">'.Yii::app()->user->getFlash('errorFile').'</div>';
				}?>
				<div class="pt-10"><a off_address="" target="_blank" class="template" href="<?php echo $this->module->assetsUrl;?>/template/import_alih_template.xlsx" title="Import Template">Import Template</a></div>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton('Import' , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
