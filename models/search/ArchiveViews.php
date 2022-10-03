<?php
/**
 * ArchiveViews
 *
 * ArchiveViews represents the model behind the search form about `ommu\archive\models\ArchiveViews`.
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
use ommu\archive\models\ArchiveViews as ArchiveViewsModel;

class ArchiveViews extends ArchiveViewsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'archive_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip', 'updated_date', 'archiveTitle', 'userDisplayname', 'levelId', 'archiveCode'], 'safe'],
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
            $query = ArchiveViewsModel::find()->alias('t');
        } else {
            $query = ArchiveViewsModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'archive archive', 
			// 'user user'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['archiveTitle', '-archiveTitle'])) || (
            (isset($params['archiveTitle']) && $params['archiveTitle'] != '') ||
            (isset($params['levelId']) && $params['levelId'] != '') ||
            (isset($params['archiveCode']) && $params['archiveCode'] != '')
        )) {
            $query->joinWith(['archive archive']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user']);
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
			't.archive_id' => isset($params['archive']) ? $params['archive'] : $this->archive_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.views' => $this->views,
			'cast(t.view_date as date)' => $this->view_date,
			'cast(t.updated_date as date)' => $this->updated_date,
			'archive.level_id' => isset($params['levelId']) ? $params['levelId'] : $this->levelId,
		]);

        if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'archive.title', $this->archiveTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'archive.code', $this->archiveCode]);

		return $dataProvider;
	}
}
