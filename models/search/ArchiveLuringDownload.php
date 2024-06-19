<?php
/**
 * ArchiveLuringDownload
 *
 * ArchiveLuringDownload represents the model behind the search form about `ommu\archive\models\ArchiveLuringDownload`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 08:16 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archive\models\ArchiveLuringDownload as ArchiveLuringDownloadModel;

class ArchiveLuringDownload extends ArchiveLuringDownloadModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'download_ip', 'download_date', 
                'archiveTitle', 'userDisplayname'], 'safe'],
			[['luring_id', 'user_id', 
                'archiveId'], 'integer'],
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
            $query = ArchiveLuringDownloadModel::find()->alias('t');
        } else {
            $query = ArchiveLuringDownloadModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'luring.archive luring', 
			// 'user user'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['archiveTitle', '-archiveTitle'])) || 
            (isset($params['archiveTitle']) && $params['archiveTitle'] != '')
        ) {
            $query->joinWith(['archive archive']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user']);
        }
        if (isset($params['archive']) && $params['archive'] != '') {
            $query->joinWith(['luring luring']);
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
			't.luring_id' => isset($params['luring']) ? $params['luring'] : $this->luring_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.download_date as date)' => $this->download_date,
			'luring.archive_id' => $this->archiveId,
		]);

		$query->andFilterWhere(['like', 't.id', $this->id])
			->andFilterWhere(['like', 't.download_ip', $this->download_ip])
			->andFilterWhere(['or',
                ['like', 'archive.title', $this->archiveTitle],
                ['like', 'archive.code', $this->archiveTitle]
            ])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
