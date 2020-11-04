<?php
/**
 * CreatorSuggestAction
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 April 2019, 14:46 WIB
 * @link https://bitbucket.org/ommu/archive
 */

namespace ommu\archive\actions;

use Yii;
use ommu\archive\models\ArchiveCreator;

class CreatorSuggestAction extends \yii\base\Action
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
		$term = Yii::$app->request->get('term');

        if ($term == null) return [];

		$model = ArchiveCreator::find()
            ->alias('t')
			->suggest()
			->andWhere(['like', 't.creator_name', $term])
			->limit(15)
			->all();

		$result = [];
        foreach ($model as $val) {
			$result[] = [
				'id' => $val->id, 
				'label' => $val->creator_name,
			];
		}
		return $result;
	}
}