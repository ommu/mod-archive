<?php if($model != false) {
	$i = 0;
	foreach($model->data as $key => $val) {
		$i++;
		$title = ucwords(strtolower($val->title));?>
		<div class="sep">
			<?php echo $title;?>
		</div>
		<?php if($i%4 == 0) {?>
			<div class="clear"></div>
		<?php }?>
<?php }
} else {
	echo 'kosong';
}?>