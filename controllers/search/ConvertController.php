<?php
/**
 * ConvertController
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	List
 *	View
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 28 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 *----------------------------------------------------------------------------------------------------------
 */

class ConvertController extends Controller
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
		$permission = ArchiveSettings::getInfo('permission');
		if($permission == 1 || ($permission == 0 && !Yii::app()->user->isGuest)) {
			$arrThemes = $this->currentTemplate('public');
			Yii::app()->theme = $arrThemes['folder'];
			$this->layout = $arrThemes['layout'];
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
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
				'actions'=>array('index','list','view'),
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
		$setting = ArchiveSettings::model()->findByPk(1, array(
			'select' => 'meta_description, meta_keyword',
		));
		
		$model=new ArchiveConverts('frontSearch');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArchiveConverts'])) {
			$model->attributes=$_GET['ArchiveConverts'];
		}
		
		$this->pageTitleShow = true;		
		$this->pageTitle = Yii::t('phrase', 'Alih Media, Bahasa dan Tulisan');
		$this->pageDescription = $setting->meta_description;
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_index', array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionList() 
	{
		$setting = ArchiveSettings::model()->findByPk(1, array(
			'select' => 'meta_description, meta_keyword',
		));

		$criteria=new CDbCriteria;
		$criteria->condition = 'publish = :publish';
		$criteria->params = array(':publish'=>1);
		$criteria->order = 'creation_date DESC';

		$dataProvider = new CActiveDataProvider('ArchiveConverts', array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>10,
			),
		));

		$this->pageTitle = Yii::t('phrase', 'Archive Converts');
		$this->pageDescription = $setting->meta_description;
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_list', array(
			'dataProvider'=>$dataProvider,
		));
	}	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$setting = ArchiveSettings::model()->findByPk(1, array(
			'select' => 'meta_keyword',
		));

		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'View Archive Converts');
		$this->pageDescription = '';
		$this->pageMeta = $setting->meta_keyword;
		$this->render('front_view', array(
			'model'=>$model,
		));
	}	
	
	protected function gridTitle($data, $row)
	{
		return $this->renderPartial('_grid_title', array(
			'data' => $data,
		), true);
	}
	
	protected function gridInformation($data, $row)
	{
		return $this->renderPartial('_grid_information', array(
			'data' => $data,
		), true);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ArchiveConverts::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='archive-converts-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
