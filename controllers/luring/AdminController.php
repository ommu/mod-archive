<?php
/**
 * AdminController
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
 *
 * AdminController implements the CRUD actions for ArchiveLurings model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Create
 *  Update
 *  View
 *  Delete
 *  RunAction
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 23:20 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\luring;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveLurings;
use ommu\archive\models\search\ArchiveLurings as ArchiveLuringsSearch;
use ommu\archive\models\ArchiveSetting;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;

class AdminController extends Controller
{
	use \ommu\traits\FileTrait;
	use \ommu\traits\DocumentTrait;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('archive') || Yii::$app->request->get('id')) {
            if (array_key_exists('archive_submenu', $this->module->params)) {
                $this->subMenu = $this->module->params['archive_submenu'];
            }
        }

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
					'publish' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function ignoreLevelField()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all ArchiveLurings models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveLuringsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
            $this->subMenuParam = $archive;
            $archive = \ommu\archive\models\Archives::findOne($archive);
            if ($archive->isFond == true) {
                if (array_key_exists('fond_submenu', $this->module->params)) {
                    $this->subMenu = $this->module->params['fond_submenu'];
                }
            }
            if (empty($archive->level->child)) {
                unset($this->subMenu[1]['childs']);
            }
            if (empty($archive->level->field) || !in_array('location', $archive->level->field)) {
                unset($this->subMenu[1]['location']);
            }
            if (!$this->ignoreLevelField() && (empty($archive->level->field) || !in_array('luring', $archive->level->field))) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
                unset($this->subMenu[1]['luring']);
            }
            if (empty($archive->level->field) || !in_array('favourites', $archive->level->field)) {
                unset($this->subMenu[2]['favourites']);
            }
        }

		$this->view->title = Yii::t('app', 'Senarai Lurings');
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
	 * Creates a new ArchiveLurings model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (!($id = Yii::$app->request->get('id'))) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $model = new ArchiveLurings(['archive_id' => $id]);

        $archive = $model->archive;
        $childs = $archive->getArchives('relation', 1)->all();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				ini_set('max_execution_time', 0);
				ob_start();

                $documents = [];

                $uploadPath = $archive::getUploadPath();
                $documentPath = join('/', [$uploadPath, 'senarai_luring_draft']);
                $verwijderenPath = join('/', [$documentPath, 'verwijderen']);
                $this->createUploadDirectory($documentPath);

                $templatePath = Yii::getAlias('@app/runtime/archive/templates');
                // cover
                $coverTemplate = join('/', [$templatePath, 'luring_cover.php']);
                $coverName =join('_', [$model->archive->code, time(), UuidHelper::uuid(), 'cover']);
                $fileName = $this->getPdf([
                    'model' => $model,
                    'archive' => $archive,
                ], $coverTemplate, $documentPath, $coverName, false, false, 'P', 'Legal');
                array_push($documents, $fileName);

                // intro
                $introTemplate = join('/', [$templatePath, 'luring_intro.php']);
                $introName =join('_', [$model->archive->code, time(), UuidHelper::uuid(), 'intro']);
                $fileName = $this->getPdf([
                    'model' => $model,
                    'archive' => $archive,
                ], $introTemplate, $documentPath, $introName, false, false, 'P', 'Legal');
                array_push($documents, $fileName);

                // senarai
                $senaraiTemplate = join('/', [$templatePath, 'luring_senarai.php']);
                $senaraiName =join('_', [$model->archive->code, time(), UuidHelper::uuid(), 'senarai']);
                $fileName = $this->getPdf([
                    'archive' => $archive,
                    'childs' => $childs,
                ], $senaraiTemplate, $documentPath, $senaraiName, false, false, 'P', 'Legal');
                array_push($documents, $fileName);

                $model->senarai_file_draft = $documents;

                if ($model->save(false, ['senarai_file_draft'])) {
                    $archive::updateAll(['senarai_file' => $model->senarai_file_draft], ['id' => $id]);
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Senarai luring success created.'));
                return $this->redirect(['manage', 'archive' => $model->archive_id]);
                //return $this->redirect(['view', 'id' => $model->id]);
	
				ob_end_flush();

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        if ($archive->isFond == true) {
            if (array_key_exists('fond_submenu', $this->module->params)) {
                $this->subMenu = $this->module->params['fond_submenu'];
            }
        }
        $this->subMenuParam = $id;
        if (empty($archive->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (empty($archive->level->field) || !in_array('location', $archive->level->field)) {
            unset($this->subMenu[1]['location']);
        }
        if (!$this->ignoreLevelField() && (empty($archive->level->field) || !in_array('luring', $archive->level->field))) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
            unset($this->subMenu[1]['luring']);
        }
        if (empty($archive->level->field) || !in_array('favourites', $archive->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

		$this->view->title = Yii::t('app', 'Generate Senarai Luring');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
			'archive' => $archive,
		]);
	}

	/**
	 * Updates an existing ArchiveLurings model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->senarai_file = UploadedFile::getInstance($model, 'senarai_file');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Senarai luring success updated.'));
                return $this->redirect(['manage', 'archive' => $model->archive_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        if (array_key_exists('luring_submenu', $this->module->params)) {
            $this->subMenu = $this->module->params['luring_submenu'];
        }
        $this->subMenuBackTo = $model->archive_id;
		$this->view->title = Yii::t('app', 'Publish Luring: {archive-id}', ['archive-id' => $model->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ArchiveLurings model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        if (array_key_exists('luring_submenu', $this->module->params)) {
            $this->subMenu = $this->module->params['luring_submenu'];
        }
        $this->subMenuBackTo = $model->archive_id;
		$this->view->title = Yii::t('app', 'Detail Luring: {archive-id}', ['archive-id' => $model->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchiveLurings model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish','modified_id'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Senarai luring success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'archive' => $model->archive_id]);
        }
	}

	/**
	 * Finds the ArchiveLurings model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchiveLurings the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveLurings::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}