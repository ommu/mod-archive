<div class="boxed clearfix">
	<?php
		$server = Utility::getConnected(Yii::app()->params['server_options']['bpad']);
		if(in_array($server, array('http://103.255.15.100','http://192.168.30.100','http://localhost','http://127.0.0.1')))
			$server = $server.'/bpadportal';
		
		$media_file = $model->media_file;
		if(!in_array(Utility::getProtocol().'://'.Yii::app()->request->serverName, Yii::app()->params['server_options']['localhost']))
			$media_file = preg_replace('('.$server.')', 'http://bpadjogja.info', $model->media_file);
		
	if($model->media_image != '-') {?>
	<img src="<?php echo Utility::getTimThumb($model->media_image, 800, 600, 3);?>" alt="<?php echo $model->title;?>">
	<?php }
	if($model->media_file != '-') {?>
	<div class="download">
		<a href="<?php echo $media_file;?>" title="<?php echo $model->title;?>"><i class="fa fa-file-pdf-o"></i> <?php echo Yii::t('phrase', 'Download');?></a>
	</div>
	<?php }?>
	<div class="box">
		<?php echo $model->body;?>
		<?php echo Yii::t('phrase', 'Created');?>: <?php echo $model->published_date;?>
	</div>
</div>