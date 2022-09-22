<?php
/**
 * HistoryController
 * @var $this ommu\archive\controllers\view\HistoryController
 * @var $model ommu\archive\models\ArchiveViewHistory
 *
 * HistoryController implements the CRUD actions for ArchiveViewHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
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
use ommu\archive\models\ArchiveViewHistory;
use ommu\archive\models\search\ArchiveViewHistory as ArchiveViewHistorySearch;
use ommu\archive\models\ArchiveSetting;
use yii\helpers\ArrayHelper;

class HistoryController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('view') || Yii::$app->request->get('id')) {
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
	 * Lists all ArchiveViewHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveViewHistorySearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($archive = Yii::$app->request->get('archiveId')) != null) {
            $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams, ['archiveId' => $archive]);
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

        if (($view = Yii::$app->request->get('view')) != null) {
            $view = \ommu\archive\models\ArchiveViews::findOne($view);
			$this->subMenuParam = $view->archive_id;
            $view->archive->isFond = $view->archive->level_id == 1 ? true : false;
            if ($view->archive->isFond == true) {
                $this->subMenu = $this->module->params['fond_submenu'];
            } else {
                if (empty($view->archive->level->child)) {
                    unset($this->subMenu[1]['childs']);
                }
                if (!in_array('location', $view->archive->level->field)) {
                    unset($this->subMenu[2]['location']);
                }
            }
		}

        if ($archive) {
			$this->subMenuParam = $archive;
			$archive = \ommu\archive\models\Archives::findOne($archive);
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

		$this->view->title = $view ? Yii::t('app', 'Histories') : Yii::t('app', 'View Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'view' => $view,
			'archive' => $archive,
		]);
	}

	/**
	 * Displays a single ArchiveViewHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

        $archive = $model->view->archive;
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
			$this->subMenuParam = $model->view->archive_id;
		}

		$this->view->title = Yii::t('app', 'Detail View History: {view-id}', ['view-id' => $model->view::htmlHardDecode($model->view->archive->title)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ArchiveViewHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive view history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'view' => $model->view_id]);
	}

	/**
	 * Finds the ArchiveViewHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArchiveViewHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveViewHistory::findOne($id)) !== null) {
			$model->view->archive->isFond = $model->view->archive->level_id == 1 ? true : false;

			return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
