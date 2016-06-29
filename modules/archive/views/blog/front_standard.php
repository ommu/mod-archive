<?php
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#archive-article .pager a').live('click', function() {
		var url = $(this).attr('href');
		//alert(url);
		
		//return false;
	});
EOP;
	$cs->registerScript('load', $js, CClientScript::POS_END);
?>

<div class="boxed">
	<?php if($model != false && !empty($model->data)) {
		$i = 0;
		foreach($model->data as $key => $val) {
			$i++;
			$title = ucwords(strtolower($val->title));?>
			<div class="sep">
				<a href="<?php echo Yii::app()->controller->createUrl('view', array('id'=>$val->id,'t'=>Utility::getUrlTitle($title),'source'=>'standard'));?>" title="<?php echo $title;?>"><?php echo $title;?></a>
				<div class="meta">
					<?php echo Yii::t('phrase', 'Created').': '.$val->published_date;?> /
					<?php echo Yii::t('phrase', 'View').': '.$val->view;?>
				</div>
				<?php echo $val->intro;?>
			</div>
			<?php if($i%4 == 0) {?>
				<div class="clear"></div>
			<?php }?>
	<?php }
	} else {
		echo 'kosong';
	}?>
</div>

<?php if($model != false && $model->nextPager != '-') {?>
<div class="pager">
	<a href="<?php echo Yii::app()->controller->createUrl('standard', array('url'=>urlencode($model->nextPager)));?>" title="<?php echo Yii::t('phrase', 'Loadmore');?>"><?php echo Yii::t('phrase', 'Loadmore');?></a>
</div>
<?php }?>