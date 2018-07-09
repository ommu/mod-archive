<?php
/**
 * Archive Converts (archive-converts)
 * @var $this ConvertController
 * @var $model ArchiveConverts
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 */

	$this->breadcrumbs=array(
		'Archive Converts'=>array('manage'),
		'Manage',
	);
	$this->menu=array(
		array(
			'label' => Yii::t('phrase', 'Filter'), 
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'search-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Filter')),
		),
		array(
			'label' => Yii::t('phrase', 'Grid Options'), 
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'grid-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Grid Options')),
		),
	);

?>

<?php //begin.Search ?>
<div class="search-form">
<?php $this->renderPartial('_search', array(
	'model'=>$model,
)); ?>
</div>
<?php //end.Search ?>

<?php //begin.Grid Option ?>
<div class="grid-form">
<?php $this->renderPartial('_option_form', array(
	'model'=>$model,
)); ?>
</div>
<?php //end.Grid Option ?>

<?php //begin.Grid Item ?>
<?php $this->widget('application.libraries.yii-traits.system.OGridView', array(
	'id'=>'archive-converts-grid',
	'dataProvider'=>$model->frontSearch(),
	'filter'=>$model,
	'pager' => array(
		'header' => '',
	),
	'summaryText' => '',
	'columns' => array(
		array(
			'name' => 'convert_title',
			'value' => array($this, 'gridTitle'),
			'type' => 'raw',
		),
		array(
			'name' => 'convert_desc',
			'value' => array($this, 'gridInformation'),
			'type' => 'raw',
		),
		array(
			'header' => Yii::t("phrase", "Year"),
			'name' => 'convert_publish_year',
			'value' => '$data->convert_publish_year',
			'type' => 'raw',
			'htmlOptions' => array(
				'class'=>'year center',
			),
		),
	),
)); ?>
<?php //end.Grid Item ?>