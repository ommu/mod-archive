<?php
/**
 * AdminController
 * @var $this AdminController
 * @var $model ArchiveLists
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
 * @created date 13 June 2016, 23:54 WIB
 * @link https://github.com/ommu/ommu-archive
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class AdminController extends Controller
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
	public function actionSuggest() 
	{
		if(Yii::app()->request->isAjaxRequest) {
			if(isset($_GET['term'])) {
				$criteria = new CDbCriteria;
				$criteria->select = 'list_id, list_code';
				$criteria->compare('list_code',strtolower($_GET['term']), true);
				$criteria->order = 'list_id ASC';
				$model = ArchiveLists::model()->findAll($criteria);

				if($model) {
					foreach($model as $items) {
						$result[] = array('id' => $items->list_id, 'value' => strtoupper($items->list_code));
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
	public function actionManage($location=null, $story=null, $type=null) 
	{
		$pageTitle = Yii::t('phrase', 'Senarai Arsip');
		if($location != null) {
			$data = ArchiveLocation::model()->findByPk($location);
			$pageTitle = Yii::t('phrase', 'Senarai Arsip: location $location_name', array ('$location_name'=>$data->location_name));
		}
		if($story != null) {
			$data = ArchiveStory::model()->findByPk($story);
			$pageTitle = Yii::t('phrase', 'Senarai Arsip: story $story_name', array ('$story_name'=>$data->story_name));
		}
		if($type != null) {
			$data = ArchiveType::model()->findByPk($type);
			$pageTitle = Yii::t('phrase', 'Senarai Arsip: type $type_name', array ('$type_name'=>$data->type_name));
		}
		
		$model=new ArchiveLists('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ArchiveLists'])) {
			$model->attributes=$_GET['ArchiveLists'];
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

		$this->pageTitle = $pageTitle;
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
		
		$list_multiple = $_POST['list_multiple'];
		echo $list_multiple = $list_multiple == 1 ? $list_multiple : 0;
		
		if(isset($_FILES['importExcel'])) {
			$fileName = CUploadedFile::getInstanceByName('importExcel');
			if(in_array(strtolower($fileName->extensionName), array('xls','xlsx'))) {
				$file = time().'_archive_'.$fileName->name;
				if($fileName->saveAs($path.'/'.$file)) {
					Yii::import('ext.php-excel-reader.OExcelReader');
					$xls = new OExcelReader($path.'/'.$file);
					
					for ($row = 2; $row <= $xls->sheets[0]['numRows']; $row++) {
						$list_code			= strtolower(trim($xls->sheets[0]['cells'][$row][1]));
						$list_title			= trim($xls->sheets[0]['cells'][$row][2]);
						$location_code			= strtolower(trim($xls->sheets[0]['cells'][$row][3]));
						$story_code				= strtolower(trim($xls->sheets[0]['cells'][$row][4]));
						$type_name				= strtolower(trim($xls->sheets[0]['cells'][$row][5]));
						$archive_numbers		= trim($xls->sheets[0]['cells'][$row][6]);
						$archive_pages			= strtolower(trim($xls->sheets[0]['cells'][$row][7]));
						$list_publish_year	= strtoupper(trim($xls->sheets[0]['cells'][$row][8]));
						$list_desc			= trim($xls->sheets[0]['cells'][$row][9]);
						
						$list_code = explode('.', $list_code);
						if($list_multiple == 0)
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
						
						if($list_code[0] == $location_code) {
							$location = ArchiveLocation::model()->findByAttributes(array('location_code' => $location_code), array(
								'select' => 'location_id, story_enable, type_enable',
							));
							if($location->story_enable == 1) {
								$list_code_type = preg_replace("/[^a-zA-Z]/","",$list_code[2]);
								$list_code_number = preg_replace("/[^0-9]/","",$list_code[2]);
								if($list_code[1] == $story_code) {
									$story = ArchiveStory::model()->findByAttributes(array('story_code' => $story_code), array(
										'select' => 'story_id',
									));
									$type = ArchiveType::model()->findByAttributes(array('type_name' => $type_name), array(
										'select' => 'type_id, type_code',
									));
									if($list_code_type == $type->type_code) {
										$model=new ArchiveLists;
										$model->location_id = $location->location_id;
										$model->type_id = $type->type_id;
										$model->story_id = $story->story_id;
										$model->list_title = $list_title;
										$model->list_desc = $list_desc;
										$model->list_type_id = $list_code_number;
										$model->list_publish_year = $list_publish_year;
										$model->list_multiple = $list_multiple;
										if($list_multiple == 0) {
											$model->archive_number_single_i = array(
												'start'=>trim($archive_numbers[0]),
												'finish'=>trim($archive_numbers[1]),
											);
										} else 
											$model->archive_number_multiple_i = $archive_numbers;
										$model->archive_pages = $archive_pages;
										$model->save();
									}
								}
							} else {
								if($location->type_enable == 1) {
									$list_code_type = preg_replace("/[^a-zA-Z]/","",$list_code[1]);
									$list_code_number = preg_replace("/[^0-9]/","",$list_code[1]);
									
									$type = ArchiveType::model()->findByAttributes(array('type_name' => $type_name), array(
										'select' => 'type_id, type_code',
									));
									if($list_code_type == $type->type_code) {
										$model=new ArchiveLists;
										$model->location_id = $location->location_id;
										$model->type_id = $type->type_id;
										$model->story_id = 0;
										$model->list_title = $list_title;
										$model->list_desc = $list_desc;
										$model->list_type_id = $list_code_number;
										$model->list_publish_year = $list_publish_year;
										$model->list_multiple = $list_multiple;
										if($list_multiple == 0) {
											$model->archive_number_single_i = array(
												'start'=>$archive_numbers[0],
												'finish'=>$archive_numbers[1],
											);
										} else
											$model->archive_number_multiple_i = $archive_numbers;
										$model->archive_pages = $archive_pages;
										$model->save();
									}
								} else {
									if($list_code_type == $list_code[1]) {
										$model=new ArchiveLists;
										$model->location_id = $location->location_id;
										$model->type_id = 0;
										$model->story_id = 0;
										$model->list_title = $list_title;
										$model->list_desc = $list_desc;
										$model->list_type_id = $list_code[1];
										$model->list_publish_year = $list_publish_year;
										$model->list_multiple = $list_multiple;
										if($list_multiple == 0) {
											$model->archive_number_single_i = array(
												'start'=>$archive_numbers[0],
												'finish'=>$archive_numbers[1],
											);
										} else
											$model->archive_number_multiple_i = $archive_numbers;	
										$model->archive_pages = $archive_pages;
										$model->save();
									}
								}
							}
						}
					}
					
					Yii::app()->user->setFlash('success', 'Import Archive Success.');
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

		$this->pageTitle = Yii::t('phrase', 'Import Senarai Arsip');
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
		
		$model=new ArchiveLists;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ArchiveLists'])) {
			$model->attributes=$_POST['ArchiveLists'];
			if($setting->auto_numbering == 0)
				$model->scenario = 'not_auto_numbering';
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Senarai arsip success created.'));
				//$this->redirect(array('view','id'=>$model->list_id));
				if($model->back_field_i == 1)
					$this->redirect(array('manage'));
				else
					$this->redirect(array('add'));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Create Senarai Arsip');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add',array(
			'model'=>$model,
			'setting'=>$setting,
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

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ArchiveLists'])) {
			$model->attributes=$_POST['ArchiveLists'];
			if($setting->auto_numbering == 0)
				$model->scenario = 'not_auto_numbering';
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('phrase', 'Senarai arsip success updated.'));
				//$this->redirect(array('view','id'=>$model->list_id));
				$this->redirect(array('manage'));
			}
		}

		$this->pageTitle = Yii::t('phrase', 'Update Senarai: $list_title', array('$list_title'=>$model->list_title));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit',array(
			'model'=>$model,
			'setting'=>$setting,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->pageTitle = Yii::t('phrase', 'View Senarai: $list_title', array('$list_title'=>$model->list_title));
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
				ArchiveLists::model()->updateAll(array(
					'publish' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				ArchiveLists::model()->updateAll(array(
					'publish' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				ArchiveLists::model()->updateAll(array(
					'publish' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				ArchiveLists::model()->deleteAll($criteria);
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
						'id' => 'partial-archive-lists',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Senarai arsip success deleted.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = Yii::t('phrase', 'Delete Senarai: $list_title', array('$list_title'=>$model->list_title));
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
		$pageTitle = Yii::t('phrase', '$title Senarai: $list_title', array('$title'=>$title, '$list_title'=>$model->list_title));

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-archive-lists',
						'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Senarai arsip success updated.').'</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $pageTitle;
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
		$model = ArchiveLists::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='archive-lists-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
