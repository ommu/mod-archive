<?php
/**
 * ArchiveViewHistory
 *
 * ArchiveViewHistory represents the model behind the search form about `ommu\archive\models\ArchiveViewHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 12 Fabruary 2020, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archive\models\ArchiveViewHistory as ArchiveViewHistoryModel;

class ArchiveViewHistory extends ArchiveViewHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'view_id'], 'integer'],
			[['view_date', 'view_ip', 'archiveTitle', 'userDisplayname', 'archiveCode', 'archiveId', 'levelId'], 'safe'],
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
            $query = ArchiveViewHistoryModel::find()->alias('t');
        } else {
            $query = ArchiveViewHistoryModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'view view',
			// 'view.archive archive'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['archiveTitle', '-archiveTitle'])) || (
            (isset($params['archiveTitle']) && $params['archiveTitle'] != '') ||
            (isset($params['archiveCode']) && $params['archiveCode'] != '') ||
            (isset($params['levelId']) && $params['levelId'] != '') ||
            (isset($params['level']) && $params['level'] != '')
        )) {
            $query->joinWith(['archive archive']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user']);
        }
        if (isset($params['archive']) && $params['archive'] != '') {
            $query->joinWith(['view view']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['levelId', '-levelId']))) {
            $query->joinWith(['archive.levelTitle levelTitle']);
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
		$attributes['archiveTitle'] = [
			'asc' => ['archive.title' => SORT_ASC],
			'desc' => ['archive.title' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['levelId'] = [
			'asc' => ['levelTitle.title' => SORT_ASC],
			'desc' => ['levelTitle.title' => SORT_DESC],
		];
		$attributes['archiveCode'] = [
			'asc' => ['archive.code' => SORT_ASC],
			'desc' => ['archive.code' => SORT_DESC],
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
			't.id' => $this->id,
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
			'archive.level_id' => $this->levelId,
			'view.archive_id' => $this->archiveId,
        ]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'archive.title', $this->archiveTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'archive.code', $this->archiveCode]);

		return $dataProvider;
	}
}
