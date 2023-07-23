<?php
/**
 * AdminController
 * @var $this ommu\archive\controllers\restoration\AdminController
 * @var $model ommu\archive\models\ArchiveRestoration
 *
 * AdminController implements the CRUD actions for ArchiveRestoration model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:22 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\restoration;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveRestoration;
use ommu\archive\models\search\ArchiveRestoration as ArchiveRestorationSearch;
use ommu\archive\models\ArchiveSetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        $setting = ArchiveSetting::find()
            ->select(['breadcrumb_param'])
            ->where(['id' => 1])
            ->one();

		$this->breadcrumbApp = $setting->breadcrumb;
		$this->breadcrumbAppParam = $setting->getBreadcrumbAppParam();
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
	 * Lists all ArchiveRestoration models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveRestorationSearch();
        $queryParams = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($archive = Yii::$app->request->get('archive')) != null) {
            $archive = \ommu\levelItem\models\Archives::findOne($archive);
        }

		$this->view->title = Yii::t('app', 'Restorations');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'archive' => $archive,
		]);
	}

	/**
	 * Creates a new ArchiveRestoration model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new ArchiveRestoration();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive restoration success created.'));
                if ($model->stayInHere) {
                    return $this->redirect(['create', 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Restoration');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing ArchiveRestoration model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Archive restoration success updated.'));
                if ($model->stayInHere) {
                    return $this->redirect(['update', 'id' => $model->id, 'stayInHere' => $model->stayInHere]);
                }
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Restoration: {archive-id}', ['archive-id' => $model->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchiveRestoration model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Restoration: {archive-id}', ['archive-id' => $model->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchiveRestoration model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive restoration success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the ArchiveRestoration model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchiveRestoration the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveRestoration::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}