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
                $this->subMenu = $this->module->params['fond_submenu'];
            } else {
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

		$searchModel = new ArchivesSearch(['isFond' => $this->isFond()]);
        if (($parent = Yii::$app->request->get('parent')) != null) {
            $searchModel = new ArchivesSearch(['isFond' => $this->isFond(), 'parent_id' => $parent]);
        }
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

		$level = null;
        if (Yii::$app->request->get('level') && Yii::$app->request->get('data') == 'yes') {
			$level = \ommu\archive\models\ArchiveLevel::findOne(Yii::$app->request->get('level'));
        }
        if (($media = Yii::$app->request->get('mediaId')) != null) {
            $media = \ommu\archive\models\ArchiveMedia::findOne($media);
        }
        if (($creator = Yii::$app->request->get('creatorId')) != null) {
            $creator = \ommu\archive\models\ArchiveCreator::findOne($creator);
        }
        if (($repository = Yii::$app->request->get('repositoryId')) != null) {
            $repository = \ommu\archive\models\ArchiveRepository::findOne($repository);
        }

        if ($parent != null) {
            $this->subMenuParam = $parent;
			$parent = Archives::findOne($parent);
            $parent->isFond = $parent->level_id == 1 ? true : false;
            if ($parent->isFond == true) {
                $this->subMenu = $this->module->params['fond_submenu'];
            }
            if (empty($parent->level->child)) {
				unset($this->subMenu[1]['childs']);
            }
            if (!in_array('location', $parent->level->field)) {
				unset($this->subMenu[2]['location']);
            }
        }

        $title = $this->isFond() ? Yii::t('app', 'Fonds') : Yii::t('app', 'Inventories');
        if ($parent) {
            if ($parent->isFond == true) {
                $title = Yii::t('app', 'Fond Childs: {code}', ['code' => $parent->code]);
            } else {
                $title = Yii::t('app', 'Inventory Childs: {level-name} {code}', ['level-name' => $parent->level->level_name_i, 'code' => $parent->code]);
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
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success created.', ['level-name' => $model->level->level_name_i, 'code' => $model->code]));
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

        if ($id != null) {
            $parent = Archives::findOne($id);
            $parent->isFond = $parent->level_id == 1 ? true : false;
            if ($parent->isFond == true) {
                $this->subMenu = $this->module->params['fond_submenu'];
            }
            $this->subMenuParam = $parent->id;
            if (!in_array('location', $parent->level->field)) {
				unset($this->subMenu[2]['location']);
            }
        }

		$this->view->title = $parent ? Yii::t('app', 'Add New Child: {level-name} {code}', ['level-name' => $parent->level->level_name_i, 'code' => $parent->code]) : Yii::t('app', 'Create Fond');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'setting' => $setting,
			'parent' => $parent,
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

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->archive_file = UploadedFile::getInstance($model, 'archive_file');
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated.', ['level-name' => $model->level->level_name_i, 'code' => $model->code]));
                if ($model->backToManage) {
                    if (strtolower($model->level->level_name_i) == 'fond') {
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

        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (!in_array('location', $model->level->field)) {
            unset($this->subMenu[2]['location']);
        }

        $this->subMenuParam = $model->id;
		$this->view->title = Yii::t('app', 'Update {level-name}: {code}', ['level-name' => $model->level->level_name_i, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'setting' => $setting,
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

        if (empty($model->level->child)) {
            unset($this->subMenu[1]['childs']);
        }
        if (!in_array('location', $model->level->field)) {
            unset($this->subMenu[2]['location']);
        }

        $this->subMenuParam = $model->id;
		$this->view->cards = false;
		$this->view->title = Yii::t('app', 'Detail {level-name}: {code}', ['level-name' => $model->level->level_name_i, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'isFond' => $model->level_id == 1 ? true : false,
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
			Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success deleted.', ['level-name' => $model->level->level_name_i, 'code' => $model->code]));
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
			'level' => $model->level->level_name_i,
			'label' => $model::htmlHardDecode($model->title),
			'inode' => $model->getArchives('count') ? true : false,
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
                Yii::$app->session->setFlash('success', Yii::t('app', '{level-name} {code} success updated location.', ['level-name' => $model->archive->level->level_name_i, 'code' => $model->archive->code]));
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

        if (empty($model->archive->level->child)) {
			unset($this->subMenu[1]['childs']);
        }
        if (!in_array('location', $model->archive->level->field)) {
			unset($this->subMenu[2]['location']);
        }

        $this->subMenuParam = $model->archive_id;
		$this->view->title = Yii::t('app', 'Storage Location {level-name}: {code}', ['level-name' => $model->archive->level->level_name_i, 'code' => $model->archive->code]);
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

		Yii::$app->session->setFlash('success', Yii::t('app', '{label} success reset location.', ['label' => !$model->archive->isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory')]));
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

		$this->view->title = Yii::t('app', 'Preview {level-name}: {code}', ['level-name' => $model->level->level_name_i, 'code' => $model->code]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_preview_document', [
			'model' => $model,
		]);
	}
}
