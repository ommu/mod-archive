<?php
/**
 * ConvertController
 * @var $this ConvertController
 * @var $model ArchiveConverts
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Suggest
 *	Manage
 *	Import
 *	Add
 *	Edit
 *	View
 *	RunAction
 *	Delete
 *	Publish
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 01:23 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
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
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			} else
				throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('suggest'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','import','add','edit','view','runaction','delete','publish'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && in_array(Yii::app()->user->level, array(1,2))',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Lists all models.
	 */
	public function actionSuggest($limit=10) 
	{
		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_GET['term'])) {
				$criteria = new CDbCriteria;
				$criteria->select = 'convert_id, location_id, category_id, convert_title, convert_publish_year, convert_multiple, archive_pages, convert_copies, convert_code';
				//$criteria->compare('convert_parent',0);
				$criteria->compare('convert_title', strtolower($_GET['term']), true);
				$criteria->compare('convert_code', strtolower($_GET['term']), true, 'OR');
				$criteria->order = 'convert_id ASC';
				$criteria->limit = $limit;
				$model = ArchiveConverts::model()->findAll($criteria);

				if($model) {
					foreach($model as $items) {
						$result[] = array(
							'id' => $items->convert_id, 
							'value' => $items->convert_title.' ('.$items->convert_code.')',
							'location' => $items->location_id, 
							'category' => $items->category_id, 
							'year' => $items->convert_publish_year, 
							'multiple' => $items->convert_multiple, 
							'page' => $items->archive_pages, 
							'copy' => $items->convert_copies, 
						);
					}
				}
			}			
			echo CJSON::encode($result);
			Yii::app()->end();
			
		} else
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage() 
	{
		$model=new ArchiveConverts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArchiveConverts'])) {
			$model->attributes=$_GET['ArchiveConverts'];
		}

		$columnTemp = array();
		if(isset($_GET['GridColumn'])) {
			foreach($_GET['GridColumn'] as $key => $val) {
				if($_GET['GridColumn'][$key] == 1) {
					$columnTemp[] = $key;
				}
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$this->pageTitle = Yii::t('phrase', 'Archive Converts Manage');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionImport() 
	{
		ini_set('max_execution_time', 0);
		ob_start();
		
		$path = 'public/archive/import';
		// Generate path directory
		if(!file_exists($path)) {
			@mkdir($path, 0755, true);

			// Add file in directory (index.php)
			$newFile = $path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($path, 0755, true);
		
		$error = array();
		
		$convert_multiple = $_POST['convert_multiple'];
		echo $convert_multiple = $convert_multiple == 1 ? $convert_multiple : 0;
		
		if(isset($_FILES['importExcel'])) {
			$fileName = CUploadedFile::getInstanceByName('importExcel');
			if(in_array(strtolower($fileName->extensionName), array('xls','xlsx'))) {
				$file = time().'_convert_'.$fileName->name;
				if($fileName->saveAs($path.'/'.$file)) {
					Yii::import('ext.excel_reader.OExcelReader');
					$xls = new OExcelReader($path.'/'.$file);
					
					for ($row = 2; $row <= $xls->sheets[0]['numRows']; $row++) {
						$convert_code			= strtolower(trim($xls->sheets[0]['cells'][$row][1]));
						$convert_title			= trim($xls->sheets[0]['cells'][$row][2]);
						$location_code			= strtolower(trim($xls->sheets[0]['cells'][$row][3]));
						$category_code			= strtolower(trim($xls->sheets[0]['cells'][$row][4]));
						$archive_numbers		= trim($xls->sheets[0]['cells'][$row][5]);
						$archive_pages			= strtolower(trim($xls->sheets[0]['cells'][$row][6]));
						$convert_copies			= strtolower(trim($xls->sheets[0]['cells'][$row][7]));
						$convert_publish_year	= strtoupper(trim($xls->sheets[0]['cells'][$row][8]));
						$convert_desc			= trim($xls->sheets[0]['cells'][$row][9]);
						
						$convert_code = explode('.', $convert_code);
						if($convert_multiple == 0)
							$archive_numbers = explode('-', $archive_numbers);
						
						else {
							$archive_numbers = explode('#', $archive_numbers);
							
							if(!empty($archive_numbers)) {
								foreach($archive_numbers as $key => $val) {
									$archive_numbers[$key] = explode('-', trim($val));
									foreach($archive_numbers[$key] as $key2 => $val2) {
										if($key2 == 0) {
											$archive_numbers[$key]['id'] = trim($archive_numbers[$key][0]);
											unset($archive_numbers[$key][0]);
										} else if($key2 == 1) {
											$archive_numbers[$key]['start'] = trim($archive_numbers[$key][1]);
											unset($archive_numbers[$key][1]);
										} else if($key2 == 2) {
											$archive_numbers[$key]['finish'] = trim($archive_numbers[$key][2]);
											unset($archive_numbers[$key][2]);
										}
									}
								}
							}
						}
						
						if($convert_code[0] == $location_code) {
							$location = ArchiveLocation::model()->findByAttributes(array('location_code' => $location_code), array(
								'select' => 'location_id',
							));
							$convert_code_number = preg_replace("/[^0-9]/","",$convert_code[2]);
							if($convert_code[1] == $category_code) {
								$category = ArchiveConvertCategory::model()->findByAttributes(array('category_code' => $category_code), array(
									'select' => 'category_id',
								));
								
								$model=new ArchiveConverts;
								$model->location_id = $location->location_id;
								$model->category_id = $category->category_id;
								$model->convert_title = $convert_title;
								$model->convert_desc = $convert_desc;
								$model->convert_cat_id = $convert_code_number;
								$model->convert_publish_year = $convert_publish_year;
								$model->convert_multiple = $convert_multiple;
								if($convert_multiple == 0) {
									$model->convert_number_single_i = array(
										'start'=>trim($archive_numbers[0]),
										'finish'=>trim($archive_numbers[1]),
									);
								} else 
									$model->convert_number_multiple_i = $archive_numbers;
								$model->archive_pages = $archive_pages;
								$model->convert_copies = $convert_copies;
								$model->save();
								
							}
						}
					}
					
					Yii::app()->user->setFlash('success', 'Import Convert Success.');
					$this->redirect(array('manage'));
					
				} else
					Yii::app()->user->setFlash('errorFile', 'Gagal menyimpan file.');
			} else
				Yii::app()->user->setFlash('errorFile', 'Hanya file .xls dan .xlsx yang dibolehkan.');
		}

		ob_end_flush();
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = 'Import Convert';
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_import');
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$setting = ArchiveSettings::model()->findByPk(1,array(
			'select' => 'auto_numbering',
		));
		
		$id = $_GET['parent'];
		if(isset($id)) {
			$parent = ArchiveConverts::model()->findByPk($id,array(
				'select' => 'convert_id, location_id, category_id, convert_title, convert_publish_year, archive_pages, convert_copies',
			));
		}
		
		$model=new ArchiveConverts;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ArchiveConverts'])) {
			$model->attributes=$_POST['ArchiveConverts'];
			if($setting->auto_numbering == 0 && $model->convert_parent == 0)
				$model->scenario = 'not_auto_numbering';
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'ArchiveConverts success created.'));
				//$this->redirect(array('view','id'=>$model->convert_id));
				if($model->back_field_i == 1)
					$this->redirect(array('manage'));
				else {
					if($model->convert_parent == 0)
						$this->redirect(array('add'));
					else
						$this->redirect(array('add','parent'=>$model->convert_parent));
				}
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Create Archive Converts');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add',array(
			'model'=>$model,
			'setting'=>$setting,
			'parent'=>$parent != null ? $parent : false,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$setting = ArchiveSettings::model()->findByPk(1,array(
			'select' => 'auto_numbering',
		));
		
		$model=$this->loadModel($id);		
		$parent = ArchiveConverts::model()->findByPk($model->convert_parent,array(
			'select' => 'convert_id, location_id, category_id, convert_title, convert_publish_year, archive_pages, convert_copies',
		));

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ArchiveConverts'])) {
			$model->attributes=$_POST['ArchiveConverts'];
			if($setting->auto_numbering == 0 && $model->convert_parent == 0)
				$model->scenario = 'not_auto_numbering';
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'ArchiveConverts success updated.'));
				//$this->redirect(array('view','id'=>$model->convert_id));
				$this->redirect(array('manage'));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Update Archive Converts');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit',array(
			'model'=>$model,
			'setting'=>$setting,
			'parent'=>$parent != null ? $parent : false,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'View Archive Converts');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view',array(
			'model'=>$model,
		));
	}	

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunAction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = $_GET['action'];

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				ArchiveConverts::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				ArchiveConverts::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				ArchiveConverts::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				ArchiveConverts::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				if($model->delete()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-archive-converts',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'ArchiveConverts success deleted.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'ArchiveConverts Delete.');
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		
		if($model->publish == 1) {
			$title = Yii::t('phrase', 'Unpublish');
			$replace = 0;
		} else {
			$title = Yii::t('phrase', 'Publish');
			$replace = 1;
		}

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-archive-converts',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'ArchiveConverts success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_publish',array(
				'title'=>$title,
				'model'=>$model,
			));
		}
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
