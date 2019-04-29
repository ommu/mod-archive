<?php
namespace ommu\archive\assets;

class AciTreeAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@ommu/archive/assets';
	
	public $css = [
		'css/custom.css',
	];

	public $js = [
		'js/acitree.js',
	];

	public $depends = [
		'ommu\archive\assets\AciTreePluginAsset',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
		'except' => [
			'AciTreeAsset.php',
			'AciTreePluginAsset.php',
		],
	];
}