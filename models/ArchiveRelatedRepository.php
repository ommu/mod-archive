<?php
/**
 * ArchiveRelatedRepository
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 April 2019, 06:21 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_related_repository".
 *
 * The followings are the available columns in table "ommu_archive_related_repository":
 * @property integer $id
 * @property integer $archive_id
 * @property integer $repository_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Archives $archive
 * @property ArchiveRepository $repository
 * @property Users $creation
 *
 */

namespace ommu\archive\models;

use Yii;
use ommu\users\models\Users;

class ArchiveRelatedRepository extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $archiveTitle;
	public $repositoryName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_related_repository';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['archive_id', 'repository_id'], 'required'],
			[['archive_id', 'repository_id', 'creation_id'], 'integer'],
			[['archive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['archive_id' => 'id']],
			[['repository_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveRepository::className(), 'targetAttribute' => ['repository_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'archive_id' => Yii::t('app', 'Archive'),
			'repository_id' => Yii::t('app', 'Repository'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'archiveTitle' => Yii::t('app', 'Archive'),
			'repositoryName' => Yii::t('app', 'Repository'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRepository()
	{
		return $this->hasOne(ArchiveRepository::className(), ['id' => 'repository_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveRelatedRepository the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveRelatedRepository(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('archive')) {
			$this->templateColumns['archiveTitle'] = [
				'attribute' => 'archiveTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->archive) ? $model->archive->title : '-';
					// return $model->archiveTitle;
				},
			];
		}
		if(!Yii::$app->request->get('repository')) {
			$this->templateColumns['repositoryName'] = [
				'attribute' => 'repositoryName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->repository) ? $model->repository->repository_name : '-';
					// return $model->repositoryName;
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
		// $this->repositoryName = isset($this->repository) ? $this->repository->repository_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
