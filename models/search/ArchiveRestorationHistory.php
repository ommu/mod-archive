<?php
/**
 * ArchiveRestorationHistory
 *
 * ArchiveRestorationHistory represents the model behind the search form about `ommu\archive\models\ArchiveRestorationHistory`.
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:23 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archive\models\ArchiveRestorationHistory as ArchiveRestorationHistoryModel;

class ArchiveRestorationHistory extends ArchiveRestorationHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'restoration_id', 'condition', 'condition_date', 'restorationArchiveId', 'creationDisplayname'], 'safe'],
			[['creation_id'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = ArchiveRestorationHistoryModel::find()->alias('t');
        } else {
            $query = ArchiveRestorationHistoryModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'restoration.archive restoration', 
			// 'creation creation'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['restorationArchiveId', '-restorationArchiveId'])) || 
            (isset($params['restorationArchiveId']) && $params['restorationArchiveId'] != '')
        ) {
            $query->joinWith(['restoration.archive restoration']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }

		$query->groupBy(['id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['restorationArchiveId'] = [
			'asc' => ['restoration.title' => SORT_ASC],
			'desc' => ['restoration.title' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
        $query->andFilterWhere([
			't.condition' => $this->condition,
			'cast(t.condition_date as date)' => $this->condition_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
		]);

		$query->andFilterWhere(['like', 't.id', $this->id])
			->andFilterWhere(['like', 't.restoration_id', $this->restoration_id])
			->andFilterWhere(['like', 'restoration.title', $this->restorationArchiveId])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
