<?php
/**
 * AdminController
 * @var $this ommu\archive\controllers\favourite\AdminController
 * @var $model ommu\archive\models\ArchiveFavourites
 *
 * AdminController implements the CRUD actions for ArchiveFavourites model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *  Delete
 *  RunAction
 *  Publish
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 October 2022, 09:35 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\favourite;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveFavourites;
use ommu\archive\models\search\ArchiveFavourites as ArchiveFavouritesSearch;
use ommu\archive\models\ArchiveSetting;

class AdminController extends Controller
{
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
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all ArchiveFavourites models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveFavouritesSearch();
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
            if (empty($archive->level->field) || !in_array('luring', $archive->level->field)) {
                unset($this->subMenu[1]['luring']);
            }
            if (empty($archive->level->field) || !in_array('favourites', $archive->level->field)) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
                unset($this->subMenu[2]['favourites']);
            }
        }

		$this->view->title = Yii::t('app', 'Bookmarks');
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
	 * Displays a single ArchiveFavourites model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
        $this->subMenuParam = $model->archive_id;

        $archive = $model->archive;
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
        if (empty($archive->level->field) || !in_array('luring', $archive->level->field)) {
            unset($this->subMenu[1]['luring']);
        }
        if (empty($archive->level->field) || !in_array('favourites', $archive->level->field)) {
            unset($this->subMenu[2]['favourites']);
        }

        if (array_key_exists('archive_submenu', $this->module->params)) {
            $this->subMenu = $this->module->params['archive_submenu'];
        }
		$this->view->title = Yii::t('app', 'Detail Bookmark: {archive-id}', ['archive-id' => $model->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchiveFavourites model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive bookmark success deleted.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * actionPublish an existing ArchiveFavourites model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish'])) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Archive bookmark success updated.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
        }
	}

	/**
	 * Finds the ArchiveFavourites model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchiveFavourites the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveFavourites::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}