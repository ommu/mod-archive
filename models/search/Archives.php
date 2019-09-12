<?php
/**
 * Archives
 *
 * Archives represents the model behind the search form about `ommu\archive\models\Archives`.
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
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
			[['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id', 'media', 'location', 'preview'], 'integer'],
			[['title', 'code', 'medium', 'archive_type', 'archive_file', 'creation_date', 'modified_date', 'updated_date', 'parentTitle', 'levelName', 'creationDisplayname', 'modifiedDisplayname', 'creator', 'repository', 'subject', 'function'], 'safe'],
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
		if(!($column && is_array($column)))
			$query = ArchivesModel::find()->alias('t');
		else
			$query = ArchivesModel::find()->alias('t')->select($column);
		$query->joinWith([
			'parent parent'
		]);
		if((isset($params['sort']) && in_array($params['sort'], ['level_id', '-level_id'])) || (isset($params['levelName']) && $params['levelName'] != ''))
			$query = $query->joinWith(['level.title level']);
		if((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != ''))
			$query = $query->joinWith(['creation creation']);
		if((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != ''))
			$query = $query->joinWith(['modified modified']);
		if(isset($params['media']) && $params['media'] != '')
			$query = $query->joinWith(['relatedMedia relatedMedia']);
		if((isset($params['creatorId']) && $params['creatorId'] != '') || (isset($params['creator']) && $params['creator'] != ''))
			$query = $query->joinWith(['relatedCreator relatedCreator', 'relatedCreator.creator relatedCreatorRltn']);
		if((isset($params['repositoryId']) && $params['repositoryId'] != '') || (isset($params['repository']) && $params['repository'] != ''))
			$query = $query->joinWith(['relatedRepository relatedRepository', 'relatedRepository.repository relatedRepositoryRltn']);
		if((isset($params['subjectId']) && $params['subjectId'] != '') || (isset($params['subject']) && $params['subject'] != ''))
			$query = $query->joinWith(['relatedSubject relatedSubject', 'relatedSubject.tag relatedSubjectRltn']);
		if((isset($params['functionId']) && $params['functionId'] != '') || (isset($params['function']) && $params['function'] != ''))
			$query = $query->joinWith(['relatedFunction relatedFunction', 'relatedFunction.tag relatedFunctionRltn']);
		if((isset($params['location']) && $params['location'] != '') || (isset($params['rackId']) && $params['rackId'] != '') || (isset($params['roomId']) && $params['roomId'] != '') || (isset($params['depoId']) && $params['depoId'] != '') || (isset($params['buildingId']) && $params['buildingId'] != ''))
			$query = $query->joinWith(['relatedLocation relatedLocation']);
		if(isset($params['depoId']) && $params['depoId'] != '')
			$query = $query->joinWith(['relatedLocation.room relatedLocationRoom']);
		if(isset($params['buildingId']) && $params['buildingId'] != '')
			$query = $query->joinWith(['relatedLocation.room.parent relatedLocationDepo']);

		$query = $query->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
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
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if(!$this->validate()) {
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
			'relatedMedia.media_id' => $this->media,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['relatedCreator.creator_id' => $params['creatorId']]);
		$query->andFilterWhere(['relatedRepository.repository_id' => $params['repositoryId']]);
		$query->andFilterWhere(['relatedSubject.tag_id' => $params['subjectId']]);
		$query->andFilterWhere(['relatedFunction.tag_id' => $params['functionId']]);
		$query->andFilterWhere(['relatedLocation.rack_id' => $params['rackId']]);
		$query->andFilterWhere(['relatedLocation.room_id' => $params['roomId']]);
		$query->andFilterWhere(['relatedLocationRoom.parent_id' => $params['depoId']]);
		$query->andFilterWhere(['relatedLocationDepo.parent_id' => $params['buildingId']]);

		if(isset($params['location']) && $params['location'] != '') {
			if($this->location == 1)
				$query->andWhere(['is not', 'relatedLocation.id', null]);
			else if($this->location == 0)
				$query->andWhere(['is', 'relatedLocation.id', null]);
		}

		if(isset($params['preview']) && $params['preview'] != '') {
			if($this->preview == 1)
				$query->andWhere(['<>', 't.archive_file', '']);
			else if($this->preview == 0)
				$query->andWhere(['=', 't.archive_file', '']);
		}

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.archive_file', $this->archive_file])
			->andFilterWhere(['like', 'parent.title', $this->parentTitle])
			->andFilterWhere(['like', 'level.message', $this->levelName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'relatedCreatorRltn.creator_namea', $this->creator])
			->andFilterWhere(['like', 'relatedRepositoryRltn.repository_name', $this->repository])
			->andFilterWhere(['like', 'relatedSubjectRltn.body', $this->subject])
			->andFilterWhere(['like', 'relatedFunctionRltn.body', $this->function]);

		return $dataProvider;
	}
}
