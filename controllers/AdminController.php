<?php
/**
 * AdminController
 * @var $this app\components\View
 * @var $model ommu\archive\models\Archives
 *
 * AdminController implements the CRUD actions for Archives model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers;

use Yii;
use app\components\Controller;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;
use ommu\archive\models\Archives;
use ommu\archive\models\search\Archives as ArchivesSearch;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'publish' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Archives models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArchivesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if(($level = Yii::$app->request->get('level')) != null)
			$level = \ommu\archive\models\ArchiveLevel::findOne($level);
		if(($media = Yii::$app->request->get('mediaId')) != null)
			$media = \ommu\archive\models\ArchiveMedia::findOne($media);
		if(($creator = Yii::$app->request->get('creatorId')) != null)
			$creator = \ommu\archive\models\ArchiveCreator::findOne($creator);
		if(($repository = Yii::$app->request->get('repositoryId')) != null)
			$repository = \ommu\archive\models\ArchiveRepository::findOne($repository);

		$this->view->title = Yii::t('app', 'Archives');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'level' => $level,
			'media' => $media,
			'creator' => $creator,
			'repository' => $repository,
		]);
	}

	/**
	 * Creates a new Archives model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$parent = Yii::$app->request->get('id');
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['fond_sidkkas', 'maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$model = new Archives();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success created.', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]));
				if($parent)
					return $this->redirect(['create', 'id'=>$model->parent_id]);
				else
					return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		if($parent != null) {
			$parent = Archives::findOne($parent);
			$this->subMenu = $this->module->params['archive_submenu'];
		}

		$this->view->title = $parent ? Yii::t('app', 'Add New Child Levels {level-name}: {title}', ['level-name' => $parent->level->level_name_i, 'title' => Archives::htmlHardDecode($parent->title)]) : Yii::t('app', 'Create Fond');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'setting' => $setting,
			'fond' => $parent ? false : true,
			'parent' => $parent,
		]);
	}

	/**
	 * Updates an existing Archives model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['fond_sidkkas', 'maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$model = $this->findModel($id);
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated.', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]));
				return $this->redirect(['update', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->subMenu = $this->module->params['archive_submenu'];
		$this->view->title = Yii::t('app', 'Update {level-name}: {title}', ['level-name' => $model->level->level_name_i, 'title' => Archives::htmlHardDecode($model->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'setting' => $setting,
			'fond' => $model->level_id == 1 ? true : false,
		]);
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$this->subMenu = $this->module->params['archive_submenu'];
		$this->view->title = Yii::t('app', 'Detail {level-name}: {title}', ['level-name' => $model->level->level_name_i, 'title' => Archives::htmlHardDecode($model->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'setting' => $setting,
		]);
	}

	/**
	 * Deletes an existing Archives model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success deleted.', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the Archives model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Archives the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Archives::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
