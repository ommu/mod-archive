<a class="title" href="<?php //echo Yii::app()->controller->createUrl('view', array('id'=>$data->list_id,'slug'=>Utility::getUrlTitle($data->list_title)))?>javascript:void(0);" title="<?php echo $data->list_title?>"><?php echo $data->list_title;?></a>
<?php echo $data->list_desc ? Utility::shortText(Utility::hardDecode($data->list_desc),60) : '';?>

<div class="meta">
	<a href="<?php echo Yii::app()->controller->createUrl('index', array('location'=>$data->location_id,'slug'=>Utility::getUrlTitle($data->location->location_name)))?>" title="<?php echo $data->location->location_name?>"><?php echo $data->location->location_name?></a> <?php echo $data->location->story_enable == '1' || $data->location->type_enable == '1' ? '/' : '';?>
	<?php if($data->location->story_enable == '1') {?>
		<a href="<?php echo Yii::app()->controller->createUrl('index', array('story'=>$data->story_id,'slug'=>Utility::getUrlTitle($data->story->story_name)))?>" title="<?php echo $data->story->story_name?>"><?php echo $data->story->story_name?></a> <?php echo $data->location->type_enable == '1' ? '/' : '';?>
	<?php }
	if($data->location->type_enable == '1') {?>
		<a href="<?php echo Yii::app()->controller->createUrl('index', array('type'=>$data->type_id,'slug'=>Utility::getUrlTitle($data->type->type_name)))?>" title="<?php echo $data->type->type_name?>"><?php echo $data->type->type_name?></a>
	<?php }?>
</div>