<?php
/**
 * LocationSuggestAction
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 31 May 2019, 17:20 WIB
 * @link https://bitbucket.org/ommu/archive
 */

namespace ommu\archive\actions;

use Yii;
use ommu\archive\models\ArchiveLocation;

class LocationSuggestAction extends \yii\base\Action
{
	public $type;

	/**
	 * {@inheritdoc}
	 */
	protected function beforeRun()
	{
		if (parent::beforeRun()) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			Yii::$app->response->charset = 'UTF-8';
		}
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		$parent = Yii::$app->request->get('parent');

		if($parent == null) return [];

		$model = ArchiveLocation::find()
			->suggest()
			->andWhere(['parent_id' => $parent])
			->andWhere(['type' => $this->type])
			->all();

		$result = [];
		foreach($model as $val) {
			$result[] = [
				'id' => $val->id, 
				'label' => $val->location_name,
			];
		}
		return $result;
	}
}