<?php
/**
 * RepositorySuggestAction
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 April 2019, 14:46 WIB
 * @link https://bitbucket.org/ommu/archive
 */

namespace ommu\archive\actions;

use Yii;
use ommu\archive\models\ArchiveRepository;

class RepositorySuggestAction extends \yii\base\Action
{
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
		$term = Yii::$app->request->get('query');

		if($term == null) return [];

		$model = ArchiveRepository::find()
			->suggest()
			->where(['like', 'repository_name', $term])
			->limit(15)
			->all();

		$result = [];
		foreach($model as $val) {
			$result[] = [
				'id' => $val->id, 
				'label' => $val->repository_name,
			];
		}
		return $result;
	}
}