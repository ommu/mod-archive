<?php
/**
 * ArchiveLurings
 *
 * ArchiveLurings represents the model behind the search form about `ommu\archive\models\ArchiveLurings`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 23:20 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archive\models\ArchiveLurings as ArchiveLuringsModel;

class ArchiveLurings extends ArchiveLuringsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'archive_id', 'creation_id', 'modified_id', 'oDownload'], 'integer'],
			[['introduction', 'senarai_file', 'creation_date', 'modified_date', 'updated_date', 
                'archiveTitle', 'creationDisplayname', 'modifiedDisplayname', 'oDownload', 'oIntro'], 'safe'],
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
            $query = ArchiveLuringsModel::find()->alias('t');
        } else {
            $query = ArchiveLuringsModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'grid grid', 
			// 'archive archive', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oDownload', '-oDownload'])) || 
            (isset($params['oDownload']) && $params['oDownload'] != '')
        ) {
            $query->joinWith(['grid grid']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['archiveTitle', '-archiveTitle'])) || 
            (isset($params['archiveTitle']) && $params['archiveTitle'] != '')
        ) {
            $query->joinWith(['archive archive']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || 
            (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')
        ) {
            $query->joinWith(['modified modified']);
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
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
        $attributes['oDownload'] = [
            'asc' => ['grid.download' => SORT_ASC],
            'desc' => ['grid.download' => SORT_DESC],
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
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		if (isset($params['oDownload']) && $params['oDownload'] != '') {
            if ($this->oDownload == 1) {
                $query->andWhere(['<>', 'grid.download', 0]);
            } else if ($this->oDownload == 0) {
                $query->andWhere(['=', 'grid.download', 0]);
            }
        }
		if (isset($params['oIntro']) && $params['oIntro'] != '') {
            if ($this->oIntro == 1) {
                $query->andWhere(['<>', 't.introduction', '']);
            } else if ($this->oIntro == 0) {
                $query->andWhere(['=', 't.introduction', '']);
            }
        }

		$query->andFilterWhere(['like', 't.introduction', $this->introduction])
			->andFilterWhere(['like', 't.senarai_file', $this->senarai_file])
			->andFilterWhere(['or', 
                ['like', 'archive.title', $this->archiveTitle],
                ['like', 'archive.code', $this->archiveTitle]
            ])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
