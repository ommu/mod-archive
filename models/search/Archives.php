<?php
/**
 * Archives
 *
 * Archives represents the model behind the search form about `ommu\archive\models\Archives`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archive\models\Archives as ArchivesModel;

class Archives extends ArchivesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id', 'media', 
                'preview', 'location', 'oView'], 'integer'],
			[['title', 'code', 'medium', 'archive_type', 'archive_date', 'archive_file', 'creation_date', 'modified_date', 'updated_date', 
                'parentTitle', 'levelName', 'creationDisplayname', 'modifiedDisplayname', 'creator', 'repository', 'subject', 'function'], 'safe'],
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
            $query = ArchivesModel::find()->alias('t');
        } else {
            $query = ArchivesModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			// 'parent parent',
			// 'level.title level',
			// 'creation creation',
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oView', '-oView'])) || 
            (isset($params['oView']) && $params['oView'] != '')
        ) {
            $query->joinWith(['grid grid']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['parentTitle', '-parentTitle'])) || 
            (isset($params['parentTitle']) && $params['parentTitle'] != '')
        ) {
            $query->joinWith(['parent parent']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['level_id', '-level_id'])) || 
            (isset($params['levelName']) && $params['levelName'] != '')
        ) {
            $query->joinWith(['level.title level']);
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
        if (isset($params['media']) && $params['media'] != '') {
            $query->joinWith(['medias medias']);
        }
        if ((isset($params['creatorId']) && $params['creatorId'] != '') || 
            (isset($params['creator']) && $params['creator'] != '')
        ) {
            $query->joinWith(['creators creators', 'creators.creator relatedCreatorRltn']);
        }
        if ((isset($params['repositoryId']) && $params['repositoryId'] != '') || 
            (isset($params['repository']) && $params['repository'] != '')
        ) {
            $query->joinWith(['repositories repositories', 'repositories.repository relatedRepositoryRltn']);
        }
        if ((isset($params['subjectId']) && $params['subjectId'] != '') || 
            (isset($params['subject']) && $params['subject'] != '')
        ) {
            $query->joinWith(['subjects subjects', 'subjects.tag relatedSubjectRltn']);
        }
        if ((isset($params['functionId']) && $params['functionId'] != '') || 
            (isset($params['function']) && $params['function'] != '')
        ) {
            $query->joinWith(['functions functions', 'functions.tag relatedFunctionRltn']);
        }

        if ((isset($params['location']) && $params['location'] != '') || 
            (isset($params['rackId']) && $params['rackId'] != '') || 
            (isset($params['roomId']) && $params['roomId'] != '') || 
            (isset($params['depoId']) && $params['depoId'] != '') || 
            (isset($params['buildingId']) && $params['buildingId'] != '')
        ) {
            $query->joinWith(['locations locations']);
        }
        if (isset($params['depoId']) && $params['depoId'] != '') {
            $query->joinWith(['locations.room relatedLocationRoom']);
        }
        if (isset($params['buildingId']) && $params['buildingId'] != '') {
            $query->joinWith(['locations.room.parent relatedLocationDepo']);
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
		$attributes['parentTitle'] = [
			'asc' => ['parent.title' => SORT_ASC],
			'desc' => ['parent.title' => SORT_DESC],
		];
		$attributes['level_id'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['levelName'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['oView'] = [
			'asc' => ['grid.view' => SORT_ASC],
			'desc' => ['grid.view' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => isset($params['id']) ? ['code' => SORT_ASC] : ['id' => SORT_DESC],
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
			't.sidkkas' => $this->sidkkas,
			't.parent_id' => isset($params['parent']) ? $params['parent'] : $this->parent_id,
			't.level_id' => isset($params['level']) ? $params['level'] : $this->level_id,
			't.archive_type' => $this->archive_type,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'medias.media_id' => $this->media,
		]);

        // related
		$query->andFilterWhere(['creators.creator_id' => $params['creatorId']]);
		$query->andFilterWhere(['repositories.repository_id' => $params['repositoryId']]);
		$query->andFilterWhere(['subjects.tag_id' => $params['subjectId']]);
		$query->andFilterWhere(['functions.tag_id' => $params['functionId']]);

        // location
		$query->andFilterWhere(['locations.rack_id' => $params['rackId']]);
		$query->andFilterWhere(['locations.room_id' => $params['roomId']]);
		$query->andFilterWhere(['relatedLocationRoom.parent_id' => $params['depoId']]);
		$query->andFilterWhere(['relatedLocationDepo.parent_id' => $params['buildingId']]);

        if (isset($params['location']) && $params['location'] != '') {
            if ($this->location == 1) {
                $query->andWhere(['is not', 'locations.id', null]);
            } else if ($this->location == 0) {
                $query->andWhere(['is', 'locations.id', null]);
            }
        }

        if ($this->isFond) {
            $query->andWhere(['t.level_id' => 1]);
        }

        if (isset($params['preview']) && $params['preview'] != '') {
            if ($this->preview == 1) {
                $query->andWhere(['<>', 't.archive_file', '']);
            } else if ($this->preview == 0) {
                $query->andWhere(['=', 't.archive_file', '']);
            }
        }

        if (isset($params['oView']) && $params['oView'] != '') {
            if ($this->oView == 1) {
                $query->andWhere(['<>', 'grid.view', 0]);
            } else if ($this->oView == 0) {
                $query->andWhere(['=', 'grid.view', 0]);
            }
        }

        if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.archive_date', $this->archive_date])
			->andFilterWhere(['like', 't.archive_file', $this->archive_file])
			->andFilterWhere(['like', 'parent.title', $this->parentTitle])
			->andFilterWhere(['like', 'level.message', $this->levelName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'relatedCreatorRltn.creator_name', $this->creator])
			->andFilterWhere(['like', 'relatedRepositoryRltn.repository_name', $this->repository])
			->andFilterWhere(['like', 'relatedSubjectRltn.body', $this->subject])
			->andFilterWhere(['like', 'relatedFunctionRltn.body', $this->function]);

		return $dataProvider;
	}
}
