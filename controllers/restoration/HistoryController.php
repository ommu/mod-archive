<?php
/**
 * HistoryController
 * @var $this ommu\archive\controllers\restoration\HistoryController
 * @var $model ommu\archive\models\ArchiveRestorationHistory
 *
 * HistoryController implements the CRUD actions for ArchiveRestorationHistory model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  View
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:23 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\restoration;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\archive\models\ArchiveRestorationHistory;
use ommu\archive\models\search\ArchiveRestorationHistory as ArchiveRestorationHistorySearch;

class HistoryController extends Controller
{
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
	 * Lists all ArchiveRestorationHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ArchiveRestorationHistorySearch();
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

        if (($restoration = Yii::$app->request->get('restoration')) != null) {
            $restoration = \ommu\uuid\models\ArchiveRestoration::findOne($restoration);
        }

		$this->view->title = Yii::t('app', 'Restoration Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'restoration' => $restoration,
		]);
	}

	/**
	 * Displays a single ArchiveRestorationHistory model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Restoration History: {restoration-id}', ['restoration-id' => $model->restoration->archive->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ArchiveRestorationHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Archive restoration history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the ArchiveRestorationHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ArchiveRestorationHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ArchiveRestorationHistory::findOne($id)) !== null) {

            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}