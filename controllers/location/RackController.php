<?php
/**
 * RackController
 * @var $this ommu\archive\controllers\location\RackController
 * @var $model ommu\archive\models\ArchiveLocation
 *
 * RackController implements the CRUD actions for ArchiveLocation model.
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
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\location;

use Yii;
use ommu\archive\controllers\location\AdminController;
use ommu\archive\models\ArchiveLocation;
use ommu\archive\models\ArchiveStorage;

class RackController extends AdminController
{
	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['suggest'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'suggest' => [
				'class' => 'ommu\archive\actions\LocationSuggestAction',
				'type' => 'rack',
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType()
	{
		return ArchiveLocation::TYPE_RACK;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionStorage() 
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$id = Yii::$app->request->get('id');

		if($id == null) return [];

		$model = ArchiveLocation::findOne($id);

		$result = [];
		if(!empty($storage = $model->getRoomStorage(true, 'title'))) {
			foreach($storage as $key => $val) {
				$result[] = [
					'id' => $key,
					'label' => $val,
				];
			}
		}
		return $result;
	}
}
