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
		//$i = 0;
		foreach($model->data as $key => $val) {
			//$i++;
			$server = Utility::getConnected(Yii::app()->params['server_options']['bpad']);
			if(in_array($server, array('http://103.255.15.100','http://192.168.30.100','http://localhost','http://127.0.0.1')))
				$server = $server.'/bpadportal';
			
			$image = $val->media_image;
			if(!in_array(Utility::getProtocol().'://'.Yii::app()->request->serverName, Yii::app()->params['server_options']['localhost']))
				$image = preg_replace('('.$server.')', 'http://bpadjogja.info', $val->media_image);
			
			$title = ucwords(strtolower($val->title));?>
			<div class="sep <?php echo $val->media_image != '-' ? 'table' : '';?>">
				<?php if($val->media_image != '-') {?>
				<img src="<?php echo Utility::getTimThumb($val->media_image, 200, 140, 1);?>" alt="<?php echo $title;?>">				
				<div class="cell">
				<?php }?>
				<a href="<?php echo Yii::app()->controller->createUrl('view', array('id'=>$val->id,'t'=>Utility::getUrlTitle($title),'source'=>'blog'));?>" title="<?php echo $title;?>"><?php echo $title;?></a>
				<div class="meta">
					<?php echo Yii::t('phrase', 'Created').': '.$val->published_date;?> /
					<?php echo Yii::t('phrase', 'View').': '.$val->view;?>
				</div>
				<?php echo $val->intro != '' && $val->intro != '-' ? '<p>'.$val->intro.'</p>' : '';?>
				<?php if($val->media_image != '-') {?></div><?php }?>
			</div>
			<?php /* if($i%4 == 0) {?>
				<div class="clear"></div>
			<?php } */?>
	<?php }
	} else {
		echo 'kosong';
	}?>
</div>

<?php if($model != false && $model->nextPager != '-') {?>
<div class="pager">
	<a href="<?php echo Yii::app()->controller->createUrl('index', array('url'=>urlencode($model->nextPager)));?>" title="<?php echo Yii::t('phrase', 'Loadmore');?>"><?php echo Yii::t('phrase', 'Loadmore');?></a>
</div>
<?php }?>