<?php
/**
 * AdminController
 * @var $this ommu\archive\controllers\AdminController
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
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\Controller;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;
use ommu\archive\models\Archives;
use ommu\archive\models\search\Archives as ArchivesSearch;
use ommu\archive\models\ArchiveRelatedLocation;
use yii\web\UploadedFile;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id'))
			$this->subMenu = $this->module->params['archive_submenu'];
	}

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
		if(($id = Yii::$app->request->get('id')) != null)
			$searchModel = new ArchivesSearch(['parent_id'=>$id]);
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

		if($id != null) {
			$parent = Archives::findOne($id);
			if(strtolower($parent->level->level_name_i) == 'item')
				unset($this->subMenu['childs']);
		}

		$this->view->title = $parent ?  Yii::t('app', 'Inventory Childs {level-name}: {title}', ['level-name' => $parent->level->level_name_i, 'title' => Archives::htmlHardDecode($parent->title)]) : Yii::t('app', 'Inventory');
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
			'parent' => $parent,
		]);
	}

	/**
	 * Creates a new Archives model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$id = Yii::$app->request->get('id');
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['fond_sidkkas', 'maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$model = new Archives();
		if(!$id)
			$model = new Archives(['level_id'=>1]);

		if(Yii::$app->request->isPost) {
			$postData = Yii::$app->request->post();
			$model->load($postData);
			$model->archive_file = UploadedFile::getInstance($model, 'archive_file');
			if(!($model->archive_file instanceof UploadedFile && !$model->archive_file->getHasError()))
				$model->archive_file = $postData['archive_file'] ? $postData['archive_file'] : '';

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success created.', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]));
				if($id)
					return $this->redirect(['create', 'id'=>$model->parent_id]);
				else
					return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if($id != null)
			$parent = Archives::findOne($id);

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
			$model->archive_file = UploadedFile::getInstance($model, 'archive_file');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated.', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]));
				return $this->redirect(['update', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if(strtolower($model->level->level_name_i) == 'item')
			unset($this->subMenu['childs']);

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

		if(strtolower($model->level->level_name_i) == 'item')
			unset($this->subMenu['childs']);

		$this->view->title = Yii::t('app', 'Detail {level-name}: {title}', ['level-name' => $model->level->level_name_i, 'title' => Archives::htmlHardDecode($model->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
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
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
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

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionData($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = Archives::findOne($id);

		if($model == null) return [];

		$codes = [];
		$result[] = $this->getData($model, $codes);

		return $result;
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function getData($model, $codes)
	{
		$data = [
			'id' => $model->id,
			'code' => $model->code,
			'level' => $model->level->level_name_i,
			'label' => $model->title,
			'inode' => $model->getArchives('count') ? true : false,
			'view-url' => Url::to(['view', 'id'=>$model->id]),
			'update-url' => Url::to(['update', 'id'=>$model->id]),
		];
		if(!empty($codes))
			$data = ArrayHelper::merge($data, ['open'=>true, 'branch'=>[$codes]]);
		
		if(isset($model->parent))
			$data = $this->getData($model->parent, $data);

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionLocation($id)
	{
		$model = ArchiveRelatedLocation::find()
			->where(['archive_id'=>$id])
			->one();
		if($model == null)
			$model = new ArchiveRelatedLocation(['archive_id'=>$id]);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated location.', ['level-name'=>$model->archive->level->level_name_i, 'code'=>$model->archive->code]));
				return $this->redirect(['location', 'id'=>$model->archive_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		if(strtolower($model->archive->level->level_name_i) == 'item')
			unset($this->subMenu['childs']);

		$this->view->title = Yii::t('app', 'Storage Location {level-name}: {title}', ['level-name' => $model->archive->level->level_name_i, 'title' => Archives::htmlHardDecode($model->archive->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_location', [
			'model' => $model,
		]);
	}
}
