<?php
/**
 * SyncController
 * @var $this SyncController
 * @var $model ArchiveLists
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Indexing
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 11 July 2016, 07:25 WIB
 * @link https://github.com/ommu/ommu-archive
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SyncController extends Controller
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
		if(!Yii::app()->user->isGuest) {
			if(Yii::app()->user->level == 1) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			} else
				throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('indexing'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 1)',
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
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndexing($index='archive') 
	{
		ini_set('max_execution_time', 0);
		ob_start();
		
		$criteria = new CDbCriteria;
		if($index == 'archive')
			$model = ArchiveLists::model()->findAll($criteria);
		else
			$model = ArchiveConverts::model()->findAll($criteria);

		if($model) {
			foreach($model as $key => $val) {
				if($index == 'archive')
					$data = ArchiveLists::model()->findByPk($val->list_id);
				else
					$data = ArchiveConverts::model()->findByPk($val->convert_id);
				$data->update();
			}
		}

		ob_end_flush();
	}
}
