<a class="title" href="<?php //echo Yii::app()->controller->createUrl('view', array('id'=>$data->convert_id,'slug'=>Utility::getUrlTitle($data->convert_title)))?>javascript:void(0);" title="<?php echo $data->convert_title?>"><?php echo $data->convert_title;?></a>
<?php echo $data->convert_desc ? Utility::shortText(Utility::hardDecode($data->convert_desc),60) : '';?>

<div class="meta">
	<a href="<?php echo Yii::app()->controller->createUrl('index', array('location'=>$data->location_id,'slug'=>Utility::getUrlTitle($data->location->location_name)))?>" title="<?php echo $data->location->location_name?>"><?php echo $data->location->location_name?></a> /
	<a href="<?php echo Yii::app()->controller->createUrl('index', array('category'=>$data->category_id,'slug'=>Utility::getUrlTitle($data->category->category_name)))?>" title="<?php echo $data->category->category_name?>"><?php echo $data->category->category_name?></a>
</div>