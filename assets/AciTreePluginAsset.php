<?php
namespace ommu\archive\assets;

class AciTreePluginAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@npm/acitree/dist';
	
	public $css = [
		'css/aciTree.css',
	];

	public $js = [
		'js/jquery.min.js',
		'js/jquery.aciPlugin.min.js',
		'js/jquery.aciTree.min.js',
	];

	public $depends = [];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
	];
}