<a class="title" href="<?php echo Yii::app()->controller->createUrl('view', array('id'=>$data->convert_id,'t'=>Utility::getUrlTitle($data->convert_title)))?>" title="<?php echo $data->convert_title?>"><?php echo Utility::shortText(Utility::hardDecode($data->convert_title),60)?></a>
<?php echo $data->convert_desc != '' ? Utility::shortText(Utility::hardDecode($data->convert_desc),60) : '';?>

<div class="meta">
	<a href="" title="<?php echo $data->location->location_name?>"><?php echo $data->location->location_name?></a> /
	<a href="" title="<?php echo $data->category->category_name?>"><?php echo $data->category->category_name?></a>
</div>