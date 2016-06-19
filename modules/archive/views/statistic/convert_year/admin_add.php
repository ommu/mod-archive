<?php
/**
 * View Archive Convert Years (view-archive-convert-year)
 * @var $this ConvertyearController
 * @var $model ViewArchiveConvertYear
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 23:33 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'View Archive Convert Years'=>array('manage'),
		'Create',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('/statistic/convert_year/_form', array('model'=>$model)); ?>
</div>
