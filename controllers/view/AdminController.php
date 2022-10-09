<?php
/**
 * AdminController
 * @var $this ommu\archive\controllers\view\AdminController
 * @var $model ommu\archive\models\ArchiveViews
 *
 * AdminController implements the CRUD actions for ArchiveViews model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 25 February 2020, 16:43 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\view;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveViews;
use ommu\archive\models\search\ArchiveViews as ArchiveViewsSearch;
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
			$this->subMenu = $this->module->params['archive_submenu'];
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
	 * Lists all ArchiveViews models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveViewsSearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($level = Yii::$app->request->get('level')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['levelId' => $level]);
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

        if (($archive = Yii::$app->request->get('archive')) != null) {
            $this->subMenuParam = $archive;
			$archive = \ommu\archive\models\Archives::findOne($archive);
            $archive->isFond = $archive->level_id == 1 ? true : false;
            if ($archive->isFond == true) {
                $this->subMenu = $this->module->params['fond_submenu'];
            } else {
                if (empty($archive->level->child)) {
                    unset($this->subMenu[1]['childs']);
                }
                if (!in_array('location', $archive->level->field)) {
                    unset($this->subMenu[2]['location']);
                }
            }
		}
        if (($user = Yii::$app->request->get('user')) != null) {
            $user = \app\models\Users::findOne($user);
        }

		$this->view->title = Yii::t('app', 'Views');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'archive' => $archive,
			'user' => $user,
		]);
	}

	/**
	 * Displays a single ArchiveViews model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        $archive = $model->archive;
        if ($archive->isFond == true) {
            $this->subMenu = $this->module->params['fond_submenu'];
        } else {
            if (empty($archive->level->child)) {
                unset($this->subMenu[1]['childs']);
            }
            if (!in_array('location', $archive->level->field)) {
                unset($this->subMenu[2]['location']);
            }
        }

        if (!Yii::$app->request->isAjax) {
			$this->subMenuParam = $model->archive_id;
		}

		$this->view->title = Yii::t('app', 'Detail View: {archive-id}', ['archive-id' => $model::htmlHardDecode($model->archive->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArchiveViews model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Archive view success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'archive' => $model->archive_id]);
		}
	}

	/**
	 * actionPublish an existing ArchiveViews model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Archive view success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'archive' => $model->archive_id]);
		}
	}

	/**
	 * Finds the ArchiveViews model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchiveViews the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveViews::findOne($id)) !== null) {
            $model->archive->isFond = $model->archive->level_id == 1 ? true : false;

			return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
