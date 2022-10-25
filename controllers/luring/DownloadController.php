<?php
/**
 * DownloadController
 * @var $this ommu\archive\controllers\luring\DownloadController
 * @var $model ommu\archive\models\ArchiveLuringDownload
 *
 * DownloadController implements the CRUD actions for ArchiveLuringDownload model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 08:16 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\luring;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveLuringDownload;
use ommu\archive\models\search\ArchiveLuringDownload as ArchiveLuringDownloadSearch;
use ommu\archive\models\ArchiveSetting;

class DownloadController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('luring')) {
            if (array_key_exists('luring_submenu', $this->module->params)) {
                $this->subMenu = $this->module->params['luring_submenu'];
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
	 * Lists all ArchiveLuringDownload models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveLuringDownloadSearch();
        $queryParams = Yii::$app->request->queryParams;
        if (($archive = Yii::$app->request->get('archive')) != null) {
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

        if (($luring = Yii::$app->request->get('luring')) != null) {
            $this->subMenuParam = $luring;
            $luring = \ommu\archive\models\ArchiveLurings::findOne($luring);
            $this->subMenuBackTo = $luring->archive_id;
        }

		$this->view->title = Yii::t('app', 'Luring Downloads');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'luring' => $luring,
		]);
	}

	/**
	 * Deletes an existing ArchiveLuringDownload model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive luring download success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'luring' => $model->luring_id]);
	}

	/**
	 * Finds the ArchiveLuringDownload model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchiveLuringDownload the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveLuringDownload::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}