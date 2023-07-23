<?php
namespace ommu\archive\assets;

class ArchiveTreeFond extends \yii\web\AssetBundle
{
	public $sourcePath = '@ommu/archive/assets';

	public $js = [
		'js/acitree-fond.js',
	];

	public $depends = [
		'ommu\archive\assets\AciTreePluginAsset',
		'ommu\archive\assets\AciTreeAsset',
	];

	public $publishOptions = [
		'forceCopy' => YII_DEBUG ? true : false,
		'except' => [
			'AciTreeAsset.php',
			'AciTreePluginAsset.php',
			'ArchiveTree.php',
			'ArchiveTreeFond.php',
		],
	];
}