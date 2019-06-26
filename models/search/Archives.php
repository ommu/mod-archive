<?php
/**
 * Archives
 *
 * Archives represents the model behind the search form about `ommu\archive\models\Archives`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
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
			[['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id', 'media'], 'integer'],
			[['title', 'code', 'medium', 'image_type', 'creation_date', 'modified_date', 'updated_date', 'parentTitle', 'levelName', 'creationDisplayname', 'modifiedDisplayname', 'creator', 'repository', 'subject', 'function'], 'safe'],
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
			'parent parent', 
			'level.title level', 
			'creation creation', 
			'modified modified', 
			'relatedMedia relatedMedia', 
			'relatedCreator relatedCreator', 
			'relatedRepository relatedRepository', 
			'relatedSubject relatedSubject', 
			'relatedFunction relatedFunction',
			'relatedCreator.creator relatedCreatorRltn', 
			'relatedRepository.repository relatedRepositoryRltn', 
			'relatedSubject.tag relatedSubjectRltn', 
			'relatedFunction.tag relatedFunctionRltn'
		]);

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
			't.image_type' => $this->image_type,
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

		if(isset($params['creatorId']) && $params['creatorId'])
			$query->andFilterWhere(['relatedCreator.creator_id' => $params['creatorId']]);

		if(isset($params['repositoryId']) && $params['repositoryId'])
			$query->andFilterWhere(['relatedRepository.repository_id' => $params['repositoryId']]);

		if(isset($params['subjectId']) && $params['subjectId'])
			$query->andFilterWhere(['relatedSubject.tag_id' => $params['subjectId']]);

		if(isset($params['functionId']) && $params['functionId'])
			$query->andFilterWhere(['relatedFunction.tag_id' => $params['functionId']]);

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.code', $this->code])
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
