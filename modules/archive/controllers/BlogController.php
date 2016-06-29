<?php
/**
 * BlogController
 * @var $this BlogController
 * @var $model Archives
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Standard
 *	Terminology
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class BlogController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','standard','terminology'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$category = '6,10';
		$tag = '';
		$paging = 'true';
		$pagesize = 10;
			
		$server = Utility::getConnected(Yii::app()->params['server_options']['bpad']);
		if($server != 'neither-connected') {
			if(!isset($_GET['url'])) {
				if(in_array($server, array('http://103.255.15.100','http://localhost','http://127.0.0.1','http://192.168.30.100')))
					$server = $server.'/bpadportal';			
				$url = $server.preg_replace('('.Yii::app()->request->baseUrl.')', '', Yii::app()->createUrl('article/api/site/list'));
				
			} else
				$url = urldecode($_GET['url']);
			
			$item = array(
				'category' => $category,
				'tag' => $tag,
				'paging' => $paging,
				'pagesize' => $pagesize,
			);
			$items = http_build_query($item);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch,CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $items);
			$output=curl_exec($ch);			
		}
		
		$this->pageTitleShow = true;	
		$this->pageTitle = Yii::t('phrase', 'Blog\'s');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('front_index',array(
			'model'=>$output === false ? false : json_decode($output),
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionStandard() 
	{
		$category = '15';
		$tag = '';
		$paging = 'true';
		$pagesize = 10;
			
		$server = Utility::getConnected(Yii::app()->params['server_options']['bpad']);
		if($server != 'neither-connected') {
			if(!isset($_GET['url'])) {
				if(in_array($server, array('http://103.255.15.100','http://localhost','http://127.0.0.1','http://192.168.30.100')))
					$server = $server.'/bpadportal';			
				$url = $server.preg_replace('('.Yii::app()->request->baseUrl.')', '', Yii::app()->createUrl('article/api/site/list'));
				
			} else
				$url = urldecode($_GET['url']);
			
			$item = array(
				'category' => $category,
				'tag' => $tag,
				'paging' => $paging,
				'pagesize' => $pagesize,
			);
			$items = http_build_query($item);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch,CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $items);
			$output=curl_exec($ch);			
		}
		
		$this->pageTitleShow = true;
		$this->pageTitle = Yii::t('phrase', 'Standar Kearsipan');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('front_standard',array(
			'model'=>$output === false ? false : json_decode($output),
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionTerminology() 
	{
		$category = '16';
		$tag = '';
		$paging = 'true';
		$pagesize = 10;
			
		$server = Utility::getConnected(Yii::app()->params['server_options']['bpad']);
		if($server != 'neither-connected') {
			if(!isset($_GET['url'])) {
				if(in_array($server, array('http://103.255.15.100','http://localhost','http://127.0.0.1','http://192.168.30.100')))
					$server = $server.'/bpadportal';			
				$url = $server.preg_replace('('.Yii::app()->request->baseUrl.')', '', Yii::app()->createUrl('article/api/site/list'));
				
			} else
				$url = urldecode($_GET['url']);
			
			$item = array(
				'category' => $category,
				'tag' => $tag,
				'paging' => $paging,
				'pagesize' => $pagesize,
			);
			$items = http_build_query($item);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch,CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $items);
			$output=curl_exec($ch);			
		}
		
		$this->pageTitleShow = true;		
		$this->pageTitle = Yii::t('phrase', 'Daftar Istilah');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('front_terminology',array(
			'model'=>$output === false ? false : json_decode($output),
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Archives::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='archives-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
