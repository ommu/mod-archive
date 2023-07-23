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
 *	Location
 *	ResetLocation
 *	Preview
 *	Tree
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\Archives;
use ommu\archive\models\search\Archives as ArchivesSearch;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use ommu\archiveLocation\models\ArchiveLocations;
use yii\web\UploadedFile;
use ommu\archive\models\ArchiveSetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('parent') || Yii::$app->request->get('id')) {
            if ($this->isFond() == true) {
                if (array_key_exists('fond_submenu', $this->module->params)) {
                    $this->subMenu = $this->module->params['fond_submenu'];
                }
            } else {
                if (array_key_exists('archive_submenu', $this->module->params)) {
                    $this->subMenu = $this->module->params['archive_submenu'];
                }
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
		// Yii::$app->input->xssClean(Yii::$app->request->get());
		// Yii::$app->input->stripTags(Yii::$app->request->get());
        // Yii::$app->input->purify(Yii::$app->request->get());
        // \yii\helpers\HtmlPurifier::process(Yii::$app->request->get('g'));
        // strip_tags(Yii::$app->request->get());
        // echo '<pre>';
        // print_r(Yii::$app->request->get());
        // echo '</pre>';

		$searchModel = new ArchivesSearch(['isFond' => $this->isFond(), 'isLuring' => false, 'isLocation' => false]);
        $queryParams = Yii::$app->request->queryParams;
        if (($parent = Yii::$app->request->get('parent')) != null) {
            $queryParams = ArrayHelper::merge($queryParams, ['parent_id' => $parent]);
        }
        if (($creator = Yii::$app->request->get('creatorId')) != null) {
            $queryParams = ArrayHelper::merge($queryParams, ['creatorId' => $creator]);
        }
        if (($repository = Yii::$app->request->get('repositoryId')) != null) {
            $queryParams = ArrayHelper::merge($queryParams, ['repositoryId' => $repository]);
        }
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

		$level = null;
        if (Yii::$app->request->get('level') && Yii::$app->request->get('data') == 'yes') {
			$level = \ommu\archive\models\ArchiveLevel::findOne(Yii::$app->request->get('level'));
        }
        if (($media = Yii::$app->request->get('media')) != null) {
            $media = \ommu\archive\models\ArchiveMedia::findOne($media);
        }
        if ($creator) {
            $creator = \ommu\archive\models\ArchiveCreator::findOne($creator);
        }
        if ($repository) {
            $repository = \ommu\archive\models\ArchiveRepository::findOne($repository);
        }

        if ($parent != null) {
            $this->subMenuParam = $parent;
			$parent = Archives::findOne($parent);
            if ($parent->isFond == true) {
                if (array_key_exists('fond_submenu', $this->module->params)) {
                    $this->subMenu = $this->module->params['fond_submenu'];
                }
            }
            if (empty($parent->level->child)) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
				unset($this->subMenu[1]['childs']);
            }
            if (empty($parent->level->field) || !in_array('location', $parent->level->field)) {
				unset($this->subMenu[1]['location']);
            }
            if (empty($parent->level->field) || !in_array('luring', $parent->level->field)) {
                unset($this->subMenu[1]['luring']);
            }
            if (empty($parent->level->field) || !in_array('favourites', $parent->level->field)) {
				unset($this->subMenu[2]['favourites']);
            }
        }

        $title = $this->isFond() ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventories');
        if ($parent) {
            if ($parent->isFond == true) {
                $title = Yii::t('app', 'Senarai Childs: {code}', ['code' => $parent->code]);
            } else {
                $title = Yii::t('app', 'Inventory Childs: {level-name} {code}', ['level-name' => $parent->levelTitle->message, 'code' => $parent->code]);
            }
        }

		$this->view->title = $title;
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
			'isFond' => $this->isFond(),
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
        if ($this->isFond() == false && !$id) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['fond_sidkkas', 'maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
			->where(['id' => 1])
			->one();

		$model = new Archives();
        if (!$id) {
			$model = new Archives(['level_id' => 1]);
        }

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $model->archive_file = UploadedFile::getInstance($model, 'archive_file');
            if (!($model->archive_file instanceof UploadedFile && !$model->archive_file->getHasError())) {
                $model->archive_file = $postData['archive_file'] ? $postData['archive_file'] : '';
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success created.', ['level-name' => $model->levelTitle->message, 'code' => $model->code]));
                if ($id && empty($model->level->child)) {
                    if ($model->backToManage) {
						return $this->redirect(['manage', 'parent' => $model->parent_id]);
                    }
					return $this->redirect(['create', 'id' => $model->parent_id]);
				}
                if ($model->backToManage) {
					return $this->redirect(['manage', 'parent' => $model->parent_id]);
                }
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $parent = null;
        $fondId = null;
        $referenceCode = [];
        if ($id != null) {
            $this->subMenuParam = $id;
            $parent = Archives::findOne($id);
            $referenceCode = $parent->referenceCode;
            if ($parent != null && array_key_exists('Fond', $referenceCode)) {
                $fondId = $referenceCode['Fond']['id'];
                $model->fond_id = $fondId;
            }

            if ($parent->isFond == true) {
                if (array_key_exists('fond_submenu', $this->module->params)) {
                    $this->subMenu = $this->module->params['fond_submenu'];
                }
            }
            if (empty($parent->level->field) || !in_array('location', $parent->level->field)) {
				unset($this->subMenu[1]['location']);
            }
            if (empty($parent->level->field) || !in_array('luring', $parent->level->field)) {
                unset($this->subMenu[1]['luring']);
            }
            if (empty($parent->level->field) || !in_array('favourites', $parent->level->field)) {
				unset($this->subMenu[2]['favourites']);
            }
        }

		$this->view->title = $parent ? Yii::t('app', 'Add New Child: {level-name} {code}', ['level-name' => $parent->levelTitle->message, 'code' => $parent->code]) : Yii::t('app', 'Create Senarai');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'setting' => $setting,
			'parent' => $parent,
			'referenceCode' => $referenceCode,
			'isFond' => $parent ? false : true,
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

        $parent = $model->parent ?? null;
        $referenceCode = $model->referenceCode;
        $fondId = null;
        if ($model->fond_id == null && $parent != null && array_key_exists('Fond', $referenceCode)) {
            $fondId = $referenceCode['Fond']['id'];
            $model->fond_id = $fondId;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->archive_file = UploadedFile::getInstance($model, 'archive_file');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated.', ['level-name' => $model->levelTitle->message, 'code' => $model->code]));
                if ($model->backToManage) {
                    if (strtolower($model->levelTitle->message) == 'fond') {
						return $this->redirect(['manage', 'level' => $model->level_id]);
                    }
					return $this->redirect(['manage', 'parent' => $model->parent_id]);
				}
				return $this->redirect(['update', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $this->subMenuParam = $model->id;
        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (empty($model->level->field) || !in_array('location', $model->level->field)) {
            unset($this->subMenu[1]['location']);
        }
        if (empty($model->level->field) || !in_array('luring', $model->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($model->level->field) || !in_array('favourites', $model->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

		$this->view->title = Yii::t('app', 'Update {level-name}: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'setting' => $setting,
			'parent' => $parent,
			'referenceCode' => $referenceCode,
			'isFond' => $model->level_id == 1 ? true : false,
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

        $this->subMenuParam = $model->id;
        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (empty($model->level->field) || !in_array('location', $model->level->field)) {
            unset($this->subMenu[1]['location']);
        }
        if (empty($model->level->field) || !in_array('luring', $model->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($model->level->field) || !in_array('favourites', $model->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

		$this->view->cards = false;
		$this->view->title = Yii::t('app', 'Detail {level-name}: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'isFond' => $model->level_id == 1 ? true : false,
			'small' => false,
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

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success deleted.', ['level-name' => $model->levelTitle->message, 'code' => $model->code]));
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
        if (($model = Archives::findOne($id)) !== null) {
            $model->isFond = $this->isFond();
            $model->media = array_flip($model->getMedias(true));
            $model->creator = implode(',', $model->getCreators(true, 'title'));
            $model->repository =  array_flip($model->getRepositories(true));
            $model->subject =  implode(',', $model->getSubjects(true, 'title'));
            $model->function =  implode(',', $model->getFunctions(true, 'title'));
            $model->location = $model->getLocations(false) != null ? 1 : 0;

			return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function isFond()
	{
		return false;
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

        if ($model == null) return [];

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
			'level' => $model->levelTitle->message,
			'label' => $model::htmlHardDecode($model->title),
			'inode' => $model->getArchives('count', 1) ? true : false,
			'view-url' => Url::to(['view', 'id' => $model->id]),
			'update-url' => Url::to(['update', 'id' => $model->id]),
			'child-url' => Url::to(['manage', 'parent' => $model->id]),
		];
        if (!empty($codes)) {
			$data = ArrayHelper::merge($data, ['open' => true, 'branch' => [$codes]]);
        }
		
        if (isset($model->parent)) {
			$data = $this->getData($model->parent, $data);
        }

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionLocation($id)
	{
		$model = ArchiveLocations::find()
			->where(['archive_id' => $id])
			->one();
		$newRecord = false;
        if ($model == null) {
			$newRecord = true;
			$model = new ArchiveLocations(['archive_id' => $id]);
        }
		$model->archive->isFond = $this->isFond();


        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated location.', ['level-name' => $model->archive->levelTitle->message, 'code' => $model->archive->code]));
                if (!Yii::$app->request->isAjax) {
					return $this->redirect(['location', 'id' => $model->archive_id]);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['location', 'id' => $model->archive_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

        $this->subMenuParam = $model->archive_id;
        if (empty($model->archive->level->child)) {
			unset($this->subMenu[1]['childs']);
        }
        if (empty($model->archive->level->field) || !in_array('location', $model->archive->level->field)) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
			unset($this->subMenu[1]['location']);
        }
        if (empty($model->archive->level->field) || !in_array('luring', $model->archive->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($model->archive->level->field) || !in_array('favourites', $model->archive->level->field)) {
			unset($this->subMenu[2]['favourites']);
        }

		$this->view->title = Yii::t('app', 'Storage Location {level-name}: {code}', ['level-name' => $model->archive->levelTitle->message, 'code' => $model->archive->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_location', [
			'model' => $model,
			'newRecord' => $newRecord,
		]);
	}

	/**
	 * Deletes an existing Archives model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionResetLocation($id)
	{
        if (($model = ArchiveLocations::find()->where(['id' => $id])->one()) === null) {
			throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $model->archive->isFond = $this->isFond();
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', '{label} success reset location.', ['label' => !$model->archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory')]));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPreview($id)
	{
		$model = $this->findModel($id);

        $this->subMenuParam = $model->archive_id;
        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (empty($model->level->field) || !in_array('location', $model->level->field)) {
            unset($this->subMenu[1]['location']);
        }
        if (empty($model->level->field) || !in_array('luring', $model->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($model->level->field) || !in_array('favourites', $model->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

		$this->view->title = Yii::t('app', 'Preview {level-name}: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_preview_document', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Archives model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionTree($id)
	{
        $model = $this->findModel($id);

        if (!(!$model->parent_id && $model->level_id === 1)) {
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $this->subMenuParam = $model->id;
        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (empty($model->level->field) || !in_array('location', $model->level->field)) {
            unset($this->subMenu[1]['location']);
        }
        if (empty($model->level->field) || !in_array('luring', $model->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($model->level->field) || !in_array('favourites', $model->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

		$this->view->title = Yii::t('app', 'Tree {level-name}: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_tree', [
			'model' => $model,
		]);
	}

	/**
	 * actionArchive an existing ArchivePengolahanFinal model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionArchiveTree($id)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$model = $this->findModel($id);
        $archiveJson = \yii\helpers\Json::decode($model->archive_json);

        $data = $model->arrayReset($model->setTreeAction($archiveJson, ['view']));

        return $data;
	}
}
