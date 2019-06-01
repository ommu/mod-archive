<?php
/**
 * RoomController
 * @var $this ommu\archive\controllers\location\RoomController
 * @var $model ommu\archive\models\ArchiveLocation
 *
 * RoomController implements the CRUD actions for ArchiveLocation model.
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
 * @author Putra Sudaryanto <putra@sudaryanto.id>
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

class RoomController extends AdminController
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
				'type' => 'room',
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType()
	{
		return ArchiveLocation::TYPE_ROOM;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionStorage() 
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$id = Yii::$app->request->get('id');

		if($id == null) return [];

		$model = ArchiveLocation::find()
			->select('id')
			->published()
			->where(['id' => $id])
			->andWhere(['type' => $this->type])
			->one();

		$storageType = ArchiveStorage::getStorage(1);
		$result = [];
		if(!empty($model->storage)) {
			foreach($model->storage as $val) {
				if(!array_key_exists($val, $storageType))
					continue;
				$result[] = [
					'id' => $val,
					'label' => $storageType[$val],
				];
			}
		}
		return $result;
	}
}
