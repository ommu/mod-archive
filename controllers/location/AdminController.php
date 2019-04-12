<?php
/**
 * AdminController
 * @var $this app\components\View
 * @var $model ommu\archive\models\ArchiveLocation
 *
 * AdminController implements the CRUD actions for ArchiveLocation model.
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
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\location;

use Yii;
use app\components\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use mdm\admin\components\AccessControl;
use ommu\archive\models\ArchiveLocation;
use ommu\archive\models\search\ArchiveLocation as ArchiveLocationSearch;
use yii\helpers\ArrayHelper;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		$this->subMenu = $this->module->params['location_submenu'];
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
	 * Lists all ArchiveLocation models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ArchiveLocationSearch(['type'=>$this->type]);
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

		$this->view->title = Yii::t('app', Inflector::pluralize($this->title));
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new ArchiveLocation model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ArchiveLocation();
		$attributes = ['location_name'=>$this->title];
		if($this->type == 'depo')
			$attributes = ArrayHelper::merge($attributes, ['parent_id'=>ArchiveLocation::getType(ArchiveLocation::TYPE_BUILDING)]);
		if($this->type == 'room')
			$attributes = ArrayHelper::merge($attributes, ['parent_id'=>ArchiveLocation::getType(ArchiveLocation::TYPE_DEPO)]);
		$model->setAttributeLabels($attributes);
		$model->type = $this->type;
		if($model->type != 'building')
			$model->scenario = ArchiveLocation::SCENARIO_NOT_BUILDING;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Physical storage {title} success created.', ['title'=>strtolower($this->title)]));
				return $this->redirect(['manage']);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create {title}', ['title' => $this->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchiveLocation model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if($model->type != 'building')
			$model->scenario = ArchiveLocation::SCENARIO_NOT_BUILDING;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Physical storage {title} success updated.', ['title'=>strtolower($this->title)]));
				return $this->redirect(['manage']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update {title}: {location-name}', ['title' => $this->title, 'location-name' => $model->location_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchiveLocation model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {title}: {location-name}', ['title' => $this->title, 'location-name' => $model->location_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArchiveLocation model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Physical storage {title} success deleted.', ['title'=>strtolower($this->title)]));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionPublish an existing ArchiveLocation model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Physical storage {title} success updated.', ['title'=>strtolower($this->title)]));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the ArchiveLocation model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchiveLocation the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ArchiveLocation::findOne($id)) !== null) {
			$attributes = ['location_name'=>$this->title];
			if($model->type == 'depo')
				$attributes = ArrayHelper::merge($attributes, ['parent_id'=>ArchiveLocation::getType(ArchiveLocation::TYPE_BUILDING)]);
			if($model->type == 'room')
				$attributes = ArrayHelper::merge($attributes, ['parent_id'=>ArchiveLocation::getType(ArchiveLocation::TYPE_DEPO)]);
			$model->setAttributeLabels($attributes);

			return $model;
		}

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'location';
	}

	/**
	 * Type of Location.
	 * @return string
	 */
	public function getType()
	{
		return ArchiveLocation::TYPE_BUILDING;
	}

	/**
	 * Title of Location.
	 * @return string
	 */
	public function getTitle()
	{
		return ArchiveLocation::getType($this->type);
	}
}
