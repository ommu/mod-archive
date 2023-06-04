<?php
/**
 * AdminController
 * @var $this app\components\View
 *
 * Reference start
 * TOC :
 *  Fond
 *  Index
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 30 December 2022, 15:28 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\sync;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveSetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (array_key_exists('setting_submenu', $this->module->params)) {
            $this->subMenu = $this->module->params['setting_submenu'];
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
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return [];
	}

	/**
	 * Index Action
	 */
	public function actionIndex()
	{
		$this->view->title = Yii::t('app', 'Synchronization');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index');
	}

	/**
	 * Fond Action
	 */
	public function actionFond()
	{
        $limit = 100;
        $cascade = false;
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $postData = Yii::$app->request->post();

            $limitForm = $postData['limit'];
            $cascadeForm = $postData['cascade'];
            if ($limitForm) {
                $limit = $limitForm;
            }
            if ($cascadeForm) {
                $cascade = $cascadeForm == 1 ? true : false;
            }

            ini_set('max_execution_time', 0);
            ob_start();

            $archives = $this->getArchiveNotFondId($limit);
            if ($archives) {
                self::setFondId($archives);
            }
	
            ob_end_flush();
        }

		$this->view->title = Yii::t('app', 'Sync FondID');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->renderModal('admin_fond');
	}

	/**
	 * Fond Action
	 */
	public function actionSetFond()
	{
        $limit = 200;
        $cascade = true;

        ini_set('max_execution_time', 0);
        ob_start();

        $archives = $this->getArchiveNotFondId($limit);
        if ($archives) {
            self::setFondId($archives);
        }
	
        ob_end_flush();

        if ($archives && $cascade) {
            return $this->redirect(['set-fond']);
        }
    }

	/**
	 * {@inheritdoc}
	 */
	public function getArchiveNotFondId($limit)
	{
        $model = Archives::find()
            ->select(['id', 'parent_id', 'level_id', 'code'])
            ->andWhere(['is', 'fond_id', null])
            ->andWhere(['sync_fond' => 0])
            ->limit($limit)
            ->all();

        return $model;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFondId($archives=null)
	{
        $i = 0;
        foreach ($archives as $val) {
            $i++;
            $referenceCode = $val->referenceCode;
            if (array_key_exists('Fond', $referenceCode)) {
                Archives::updateAll(['fond_id' => $referenceCode['Fond']['id'], 'sync_fond' => 1], ['id' => $val->id]);
            } else {
                Archives::updateAll(['sync_fond' => 1], ['id' => $val->id]);
            }
            Yii::$app->broadcaster->publish('devtool', ['message' => Yii::t('app', '#{id} {referencce} success sync fondId', ['id' => $val->id, 'id' => $val->code])]);
        }

        Yii::$app->broadcaster->publish('devtool', ['message' => '================']);
        Yii::$app->broadcaster->publish('devtool', ['message' => Yii::t('app', '{items} success sync', ['items' => $i])]);

        return;
	}
}
